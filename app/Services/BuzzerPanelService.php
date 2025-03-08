<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class BuzzerPanelService
{
    private $apiUrl;
    private $apiKey;
    private $secretKey;

    public function __construct()
    {
        // Ambil nilai dari config
        $this->apiUrl = config('services.buzzerpanel.url');
        $this->apiKey = config('services.buzzerpanel.api_key');
        $this->secretKey = config('services.buzzerpanel.secret_key');
    }

    private function connect($endpoint, $postData)
    {
        $response = Http::asForm()->post($endpoint, $postData);

        if ($response->successful()) {
            return $response->json();
        }

        return false;
    }

    public function getServices()
    {
        $postData = [
            'api_key' => $this->apiKey,
            'secret_key' => $this->secretKey,
            'action' => 'services'
        ];

        return $this->connect($this->apiUrl, $postData);
    }

    public function createOrder($serviceId, $data, $quantity)
    {
        $postData = [
            'api_key' => $this->apiKey,
            'action' => 'order',
            'secret_key' => $this->secretKey,
            'service' => $serviceId,
            'data' => $data,
            'quantity' => $quantity
        ];

        return $this->connect($this->apiUrl, $postData);
    }

    public function checkOrderStatus($orderId)
    {
        $postData = [
            'api_key' => $this->apiKey,
            'secret_key' => $this->secretKey,
            'action' => 'status',
            'id' => $orderId
        ];

        return $this->connect($this->apiUrl, $postData);
    }

    public function getProfile()
    {
        $postData = [
            'api_key' => $this->apiKey,
            'secret_key' => $this->secretKey,
            'action' => 'profile'
        ];

        return $this->connect($this->apiUrl, $postData);
    }
}
