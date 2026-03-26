<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class SSLCommerzService
{
    protected string $storeId;
    protected string $storePassword;
    protected string $baseUrl;

    public function __construct()
    {
        $this->storeId       = env('SSLCZ_STORE_ID');
        $this->storePassword = env('SSLCZ_STORE_PASSWORD');
        $this->baseUrl       = env('SSLCZ_SANDBOX', true)
            ? 'https://sandbox.sslcommerz.com'
            : 'https://securepay.sslcommerz.com';
    }

    public function initiatePayment(array $data): array
    {
        $postData = [
            'store_id'         => $this->storeId,
            'store_passwd'     => $this->storePassword,
            'total_amount'     => $data['amount'],
            'currency'         => 'BDT',
            'tran_id'          => $data['tran_id'],
            'success_url'      => $data['success_url'],
            'fail_url'         => $data['fail_url'],
            'cancel_url'       => $data['cancel_url'],
            'cus_name'         => $data['cus_name'],
            'cus_email'        => $data['cus_email'],
            'cus_phone'        => '01700000000',
            'cus_add1'         => 'Dhaka',
            'cus_city'         => 'Dhaka',
            'cus_country'      => 'Bangladesh',
            'cus_postcode'     => '1200',
            'shipping_method'  => 'NO',
            'product_name'     => $data['product_name'],
            'product_category' => 'Digital',
            'product_profile'  => 'non-physical-goods',
            'value_a'          => $data['value_a'], // user_id
            'value_b'          => $data['value_b'], // post_id
            'value_c'          => $data['tran_id'],
        ];

        $response = Http::asForm()->post(
            $this->baseUrl . '/gwprocess/v4/api.php',
            $postData
        );

        return $response->json();
    }

    public function validatePayment(string $valId): array
    {
        $response = Http::get(
            $this->baseUrl . '/validator/api/validationserverAPI.php',
            [
                'val_id'       => $valId,
                'store_id'     => $this->storeId,
                'store_passwd' => $this->storePassword,
                'format'       => 'json',
            ]
        );

        return $response->json();
    }
}
