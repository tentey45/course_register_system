<?php

return [

    /*
    |--------------------------------------------------------------------------
    | ABA PayWay Configuration
    |--------------------------------------------------------------------------
    |
    | All ABA PayWay Sandbox / Production credentials are stored in .env.
    | Never hardcode these values — always use the config() helper or the
    | env() calls below (and only within this file).
    |
    | Sandbox base URL : https://checkout-sandbox.payway.com.kh
    | Production base URL: https://checkout.payway.com.kh
    |
    */

    'merchant_id' => env('ABA_PAYWAY_MERCHANT_ID'),

    /**
     * API key (HMAC-SHA512 signing key).
     * This is labelled "API Key" in the ABA PayWay merchant dashboard.
     */
    'api_key' => env('ABA_PAYWAY_API_KEY'),

    /**
     * Base URL for the PayWay gateway (no trailing slash).
     * Switch to production URL when going live.
     */
    'base_url' => env('ABA_PAYWAY_BASE_URL', 'https://checkout-sandbox.payway.com.kh'),

    /**
     * The URL ABA will redirect the student's browser back to after payment.
     * Must be publicly reachable by ABA's servers.
     * Example: https://yourdomain.com/student/payment/aba/return
     */
    'return_url' => env('ABA_PAYWAY_RETURN_URL'),

    /**
     * Checkout endpoint path (appended to base_url).
     */
    'purchase_path' => '/api/payment-gateway/v1/payments/purchase',

    /**
     * Check-transaction endpoint path (appended to base_url).
     */
    'check_transaction_path' => '/api/payment-gateway/v1/payments/check-transaction-2',

];
