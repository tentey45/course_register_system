<?php

namespace App\Services\Payment;

use App\Models\Payment;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class AbaPayWayService
{
    protected string $merchantId;
    protected string $apiKey;
    protected string $baseUrl;
    protected string $returnUrl;

    public function __construct()
    {
        $this->merchantId = (string) config('services.aba_payway.merchant_id');
        $this->apiKey = (string) config('services.aba_payway.api_key');
        $this->baseUrl = rtrim((string) config('services.aba_payway.base_url'), '/');
        $this->returnUrl = (string) config('services.aba_payway.return_url');
    }

    /**
     * Generate a unique transaction id for a new payment.
     * ABA requires this to be unique per merchant.
     */
    public function generateTransactionId(): string
    {
        return 'SCRS' . now()->format('YmdHis') . strtoupper(Str::random(6));
    }

    /**
     * Build the field set + hash needed for ABA PayWay's hosted "Purchase"
     * checkout form. The browser auto-submits these fields (POST) to
     * {base_url}/api/payment-gateway/v1/payments/purchase — ABA then shows
     * its own hosted payment page and redirects back to return_url.
     *
     * NOTE: ABA occasionally adjusts which optional fields it expects in
     * the hash. Test this in the sandbox first — if the hash is rejected,
     * compare this field list/order against the current sample code in
     * your ABA PayWay merchant dashboard and adjust here (this is the only
     * place that needs to change).
     */
    public function buildCheckoutFields(Payment $payment): array
    {
        $reqTime = now()->format('YmdHis');

        $items = base64_encode(json_encode([
            [
                'name' => 'Course Registration - ' . $payment->course->course_code,
                'quantity' => 1,
                'price' => number_format((float) $payment->amount, 2, '.', ''),
            ],
        ]));

        $amount = number_format((float) $payment->amount, 2, '.', '');

        $fields = [
            'req_time' => $reqTime,
            'merchant_id' => $this->merchantId,
            'tran_id' => $payment->transaction_id,
            'amount' => $amount,
            'items' => $items,
            'shipping' => '',
            'firstname' => $payment->student->name,
            'lastname' => '',
            'email' => $payment->student->email,
            'phone' => '',
            'type' => 'purchase',
            'payment_option' => '', // '' = let student pick ABA Pay / KHQR / card on ABA's page
            'return_url' => $this->returnUrl,
            'cancel_url' => route('student.payment.cancelled', $payment->id),
            'continue_success_url' => route('student.payment.status', $payment->id),
            'return_deeplink' => '',
            'currency' => $payment->currency,
            'custom_fields' => '',
            'return_params' => (string) $payment->id,
        ];

        $fields['hash'] = $this->generateHash($fields);

        return $fields;
    }

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

    public function checkoutUrl(): string
    {
        return $this->baseUrl . '/api/payment-gateway/v1/payments/purchase';
    }

    /**
     * Ask ABA for the current status of a transaction (used from the
     * return/callback route to confirm before marking a payment as paid —
     * never trust the return_url redirect alone).
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

        $response = Http::asForm()->post($this->baseUrl . '/api/payment-gateway/v1/payments/check-transaction-2', [
            'req_time' => $reqTime,
            'merchant_id' => $this->merchantId,
            'tran_id' => $tranId,
            'hash' => $hash,
        ]);

        return $response->json() ?? [];
    }
}