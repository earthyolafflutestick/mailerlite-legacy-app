<?php

namespace App\Mailerlite;

use Illuminate\Support\Facades\Http;

class MailerLiteClient
{
    public const BASE_URL = 'https://api.mailerlite.com';

    private $apiKey;

    public function __construct($apiKey)
    {
        $this->apiKey = $apiKey;
    }

    public function setApiKey($apiKey)
    {
        $this->apiKey = $apiKey;
    }

    public function getSubscribers()
    {
        return $this->request()->get('/api/v2/subscribers');
    }

    public function getSubscriber($id)
    {
        return $this->request()->get("/api/v2/subscribers/{$id}");
    }

    public function createSubscriber($email, $name, $country)
    {
        return $this->request()->withBody(json_encode([
            'email' => $email,
            'name' => $name,
            'fields' => [
                'country' => $country,
            ]
        ]), 'application/json')->post('/api/v2/subscribers');
    }

    public function updateSubscriber($id, $name, $country)
    {
        return $this->request()->withBody(json_encode([
            'name' => $name,
            'fields' => [
                'country' => $country,
            ]
        ]), 'application/json')->put("/api/v2/subscribers/{$id}");
    }

    public function deleteSubscriber($id)
    {
        return $this->request()->delete("/api/v2/subscribers/{$id}");
    }

    public function getStats()
    {
        return $this->request()->get('/api/v2/stats');
    }

    public function request()
    {
        return Http::baseUrl(self::BASE_URL)
            ->contentType('application/json')
            ->acceptJson()
            ->withHeaders([
                'X-MailerLite-ApiKey' => $this->apiKey
            ]);
    }
}
