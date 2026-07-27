<?php

namespace App\Services\Payment;

use App\Models\Payment;
use Illuminate\Support\Facades\Http;

class BakongKhqrService
{
    protected string $accountId;
    protected string $merchantName;
    protected string $merchantCity;
    protected string $apiToken;

    public function __construct()
    {
        $this->accountId = (string) config('services.bakong.account_id');
        $this->merchantName = (string) config('services.bakong.merchant_name');
        $this->merchantCity = (string) config('services.bakong.merchant_city', 'Phnom Penh');
        $this->apiToken = (string) config('services.bakong.api_token');
    }

    /**
     * Build a KHQR (EMVCo-based) payload string for a specific amount.
     *
     * This follows the publicly documented KHQR tag structure (individual
     * account format). Bakong can revise field requirements over time —
     * before going live, generate one QR here and confirm it scans
     * correctly in a real banking app connected to Bakong, and cross-check
     * against NBC's current KHQR technical spec / sandbox tools.
     */
    public function generate(Payment $payment): string
    {
        $currencyCode = $payment->currency === 'KHR' ? '116' : '840'; // ISO 4217 numeric
        $amount = number_format((float) $payment->amount, 2, '.', '');

        $merchantAccountInfo =
            $this->tlv('00', 'KHQR01') .           // Globally Unique Identifier for Bakong
            $this->tlv('01', $this->accountId);    // Bakong account ID (e.g. name@bank)

        $additionalData = $this->tlv('01', $payment->transaction_id); // bill number

        $payload =
            $this->tlv('00', '01') .                                   // Payload Format Indicator
            $this->tlv('01', '12') .                                   // Point of Initiation: 12 = dynamic (one-time amount)
            $this->tlv('29', $merchantAccountInfo) .                   // Merchant Account Info (Bakong)
            $this->tlv('52', '5999') .                                 // Merchant Category Code (generic)
            $this->tlv('53', $currencyCode) .                          // Transaction Currency
            $this->tlv('54', $amount) .                                // Transaction Amount
            $this->tlv('58', 'KH') .                                   // Country Code
            $this->tlv('59', substr($this->merchantName, 0, 25)) .     // Merchant Name
            $this->tlv('60', substr($this->merchantCity, 0, 15)) .     // Merchant City
            $this->tlv('62', $additionalData);                        // Additional Data (bill/order ref)

        // CRC is calculated over everything so far + the "63" tag header + length "04"
        $payloadForCrc = $payload . '6304';
        $crc = strtoupper(str_pad(dechex($this->crc16($payloadForCrc)), 4, '0', STR_PAD_LEFT));

        return $payloadForCrc . $crc;
    }

    protected function tlv(string $id, string $value): string
    {
        return $id . str_pad((string) strlen($value), 2, '0', STR_PAD_LEFT) . $value;
    }

    /**
     * CRC-16/CCITT-FALSE — the checksum algorithm EMVCo QR codes use.
     */
    protected function crc16(string $data): int
    {
        $crc = 0xFFFF;
        for ($i = 0; $i < strlen($data); $i++) {
            $crc ^= (ord($data[$i]) << 8);
            for ($j = 0; $j < 8; $j++) {
                $crc = ($crc & 0x8000) ? (($crc << 1) ^ 0x1021) : ($crc << 1);
                $crc &= 0xFFFF;
            }
        }
        return $crc;
    }

    /**
     * Ask Bakong's Open API whether a transaction has actually been paid.
     * We identify the transaction by the MD5 of the QR string we generated
     * (this is how Bakong's "check by MD5" endpoint works).
     */
    public function checkTransactionPaid(string $qrString): array
    {
        $md5 = md5($qrString);

        $response = Http::withToken($this->apiToken)
            ->post('https://api-bakong.nbc.gov.kh/v1/check_transaction_by_md5', [
                'md5' => $md5,
            ]);

        return $response->json() ?? [];
    }
}