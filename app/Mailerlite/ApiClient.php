<?php

namespace App\Mailerlite;

use Illuminate\Support\Facades\Http;

class ApiClient
{
    public const BASE_URL = 'https://api.mailerlite.com';

    private $apiKey;

    public function __construct($apiKey)
    {
        $this->apiKey = $apiKey;
    }

    public function getSubscribers($offset, $limit)
    {
        return $this->request()->get('/api/v2/subscribers', [
            'offset' => $offset,
            'limit' => $limit,
        ]);
    }

    public function searchSubscribers($query, $offset, $limit)
    {
        return $this->request()->get('/api/v2/subscribers/search', [
            'query' => $query,
            'offset' => $offset,
            'limit' => $limit,
        ]);
    }

    public function createSubscriber($email, $name, $country)
    {
        return $this->request()->post('/api/v2/subscribers', [
            'email' => $email,
            'name' => $name,
            'country' => $country,
        ]);
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
