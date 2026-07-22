<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use RuntimeException;

class SslCommerzService
{
    public function initiate(array $payload): array
    {
        $storeId = config('services.sslcommerz.store_id');
        $storePassword = config('services.sslcommerz.store_password');

        if (! $storeId || ! $storePassword) {
            throw new RuntimeException('SSLCommerz credentials are missing.');
        }

        $response = Http::asForm()
            ->timeout(30)
            ->post($this->initUrl(), array_merge($payload, [
                'store_id' => $storeId,
                'store_passwd' => $storePassword,
                'product_category' => $payload['product_category'] ?? 'Subscription',
                'product_profile' => $payload['product_profile'] ?? 'non-physical-goods',
                'shipping_method' => $payload['shipping_method'] ?? 'NO',
            ]));

        if (! $response->successful()) {
            throw new RuntimeException('SSLCommerz session request failed.');
        }

        $data = $response->json();

        if (! is_array($data) || empty($data['GatewayPageURL'])) {
            throw new RuntimeException($data['failedreason'] ?? 'SSLCommerz gateway URL was not returned.');
        }

        return $data;
    }

    public function validate(string $validationId): array
    {
        $storeId = config('services.sslcommerz.store_id');
        $storePassword = config('services.sslcommerz.store_password');

        if (! $storeId || ! $storePassword) {
            throw new RuntimeException('SSLCommerz credentials are missing.');
        }

        $response = Http::timeout(30)->get($this->validationUrl(), [
            'val_id' => $validationId,
            'store_id' => $storeId,
            'store_passwd' => $storePassword,
            'v' => 1,
            'format' => 'json',
        ]);

        if (! $response->successful()) {
            throw new RuntimeException('SSLCommerz validation request failed.');
        }

        $data = $response->json();

        if (! is_array($data)) {
            throw new RuntimeException('SSLCommerz validation response was invalid.');
        }

        return $data;
    }

    private function initUrl(): string
    {
        return config('services.sslcommerz.testmode')
            ? config('services.sslcommerz.sandbox_url')
            : config('services.sslcommerz.live_url');
    }

    private function validationUrl(): string
    {
        return config('services.sslcommerz.testmode')
            ? config('services.sslcommerz.sandbox_validation_url')
            : config('services.sslcommerz.live_validation_url');
    }
}
