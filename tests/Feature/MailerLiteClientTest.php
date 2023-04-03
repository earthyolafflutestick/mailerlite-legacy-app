<?php

namespace Tests\Feature;

use App\Mailerlite\ApiClient;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Tests\TestCase;

class MailerLiteClientTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        $this->client = new ApiClient('');
    }

    public function test_get_subscribers_parameters()
    {
        Http::fake();

        $this->client->getSubscribers(10, 20);

        Http::assertSent(function (Request $request) {
            return Str::startsWith($request->url(), 'https://api.mailerlite.com/api/v2/subscribers') &&
                data_get($request->data(), 'offset') === 10 &&
                data_get($request->data(), 'limit') === 20 &&
                $request->method() === 'GET';
        });
    }

    public function test_search_subscribers_parameters()
    {
        Http::fake();

        $this->client->searchSubscribers('test@test.com', 10, 20);

        Http::assertSent(function (Request $request) {
            return Str::startsWith($request->url(), 'https://api.mailerlite.com/api/v2/subscribers/search') &&
                data_get($request->data(), 'query') === 'test@test.com' &&
                data_get($request->data(), 'offset') === 10 &&
                data_get($request->data(), 'limit') === 20 &&
                $request->method() === 'GET';
        });
    }

    public function test_create_subscriber_parameters()
    {
        Http::fake();

        $this->client->createSubscriber('test@test.com', 'Test', 'Nowhere');

        Http::assertSent(function (Request $request) {
            return Str::startsWith($request->url(), 'https://api.mailerlite.com/api/v2/subscribers') &&
                data_get($request->data(), 'email') === 'test@test.com' &&
                data_get($request->data(), 'name') === 'Test' &&
                data_get($request->data(), 'country') === 'Nowhere' &&
                $request->method() === 'POST';
        });
    }
}
