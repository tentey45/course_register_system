<?php

namespace App\Services\Payment;

use App\Models\Payment;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

/**
 * ABA PayWay Hosted Checkout Service
 *
 * Handles all communication with the ABA PayWay gateway:
 *   - Building the signed checkout field set (purchase form)
 *   - Verifying a transaction's status server-side
 *
 * All credentials are read from config/payway.php which reads
 * from .env — they are never hardcoded here.
 *
 * Sandbox docs: https://developer.aba.com.kh/
 * Dashboard:    https://payway.aba.com.kh/
 */
class AbaPayWayService
{
    protected string $merchantId;
    protected string $apiKey;
    protected string $baseUrl;
    protected string $returnUrl;
    protected string $purchasePath;
    protected string $checkTransactionPath;

    public function __construct()
    {
        $this->merchantId           = (string) config('payway.merchant_id');
        $this->apiKey               = (string) config('payway.api_key');
        $this->baseUrl              = rtrim((string) config('payway.base_url'), '/');
        $this->returnUrl            = (string) config('payway.return_url');
        $this->purchasePath         = (string) config('payway.purchase_path', '/api/payment-gateway/v1/payments/purchase');
        $this->checkTransactionPath = (string) config('payway.check_transaction_path', '/api/payment-gateway/v1/payments/check-transaction-2');
    }

    /**
     * Generate a unique transaction ID for a new payment.
     * ABA requires this to be unique per merchant.
     */
    public function generateTransactionId(): string
    {
        return 'SCRS' . now()->format('YmdHis') . strtoupper(Str::random(6));
    }

    /**
     * Build the signed field set for ABA PayWay's hosted "Purchase" form.
     *
     * The browser auto-submits these fields (POST) to the purchase endpoint.
     * ABA shows its hosted payment page and redirects back to return_url.
     *
     * Hash field order matters — if ABA returns "INVALID HASH", compare
     * the $orderedKeys list in generateHash() against the current sample
     * code in your ABA PayWay merchant dashboard and adjust there only.
     */
    public function buildCheckoutFields(Payment $payment): array
    {
        $reqTime = now()->format('YmdHis');

        $items = base64_encode(json_encode([
            [
                'name'     => 'Course Registration — ' . $payment->course->course_code,
                'quantity' => 1,
                'price'    => number_format((float) $payment->amount, 2, '.', ''),
            ],
        ]));

        $amount = number_format((float) $payment->amount, 2, '.', '');

        $fields = [
            'req_time'             => $reqTime,
            'merchant_id'          => $this->merchantId,
            'tran_id'              => $payment->transaction_id,
            'amount'               => $amount,
            'items'                => $items,
            // ABA Sandbox v3 validates this as a numeric value when using hosted checkout.
            'shipping'             => '0.00',
            'firstname'            => $payment->student->name,
            'lastname'             => '',
            'email'                => $payment->student->email,
            'phone'                => '',
            'type'                 => 'purchase',
            'payment_option'       => '',  // '' = student picks on ABA's page
            'return_url'           => $this->returnUrl,
            'cancel_url'           => route('student.payment.pending', $payment->id),
            'continue_success_url' => route('student.payment.pending', $payment->id),
            'return_deeplink'      => '',
            'currency'             => $payment->currency,
            'custom_fields'        => '',
            'return_params'        => (string) $payment->id,
        ];

        $fields['hash'] = $this->generateHash($fields);

        return $fields;
    }

    /**
     * Generate the HMAC-SHA512 hash ABA uses to verify the request.
     *
     * Field order MUST match ABA's documented order exactly.
     * Only change this list if ABA's sandbox explicitly rejects the hash
     * and their updated sample code shows a different field order.
     */
    protected function generateHash(array $fields): string
    {
        $orderedKeys = [
            'req_time', 'merchant_id', 'tran_id', 'amount', 'items', 'shipping',
            'firstname', 'lastname', 'email', 'phone', 'type', 'payment_option',
            'return_url', 'cancel_url', 'continue_success_url', 'return_deeplink',
            'currency', 'custom_fields', 'return_params',
        ];

        $stringToHash = '';
        foreach ($orderedKeys as $key) {
            $stringToHash .= $fields[$key] ?? '';
        }

        return base64_encode(hash_hmac('sha512', $stringToHash, $this->apiKey, true));
    }

    /**
     * The full URL of ABA's hosted checkout endpoint.
     * The auto-submitted form POSTs to this URL.
     */
    public function checkoutUrl(): string
    {
        return $this->baseUrl . $this->purchasePath;
    }

    /**
     * Verify a transaction's status directly with ABA's API.
     *
     * IMPORTANT: Always call this server-side before marking a payment as
     * paid. Never trust the return_url redirect parameters alone — they
     * can be spoofed by a malicious actor.
     *
     * Returns the decoded JSON response from ABA, or an empty array on
     * network/gateway failure (caller should treat this as "not confirmed").
     */
    public function checkTransaction(string $tranId): array
    {
        $reqTime = now()->format('YmdHis');

        $hash = base64_encode(hash_hmac(
            'sha512',
            $reqTime . $this->merchantId . $tranId,
            $this->apiKey,
            true
        ));

        try {
            $response = Http::timeout(15)
            ->asJson()
                ->post($this->baseUrl . $this->checkTransactionPath, [
                    'req_time'    => $reqTime,
                    'merchant_id' => $this->merchantId,
                    'tran_id'     => $tranId,
                    'hash'        => $hash,
                ]);

            return $response->json() ?? [];
        } catch (\Throwable $e) {
            Log::error('ABA PayWay check-transaction request failed', [
                'tran_id' => $tranId,
                'error'   => $e->getMessage(),
            ]);
            return [];
        }
    }
}
