<?php

namespace Tests\Feature;

use App\Mailerlite\MailerLiteClient;
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

        $this->client = new MailerLiteClient('');
    }

    public function test_get_subscribers_parameters()
    {
        Http::fake();

        $this->client->getSubscribers(10, 20);

        Http::assertSent(function (Request $request) {
            return Str::startsWith($request->url(), 'https://api.mailerlite.com/api/v2/subscribers') &&
                $request->method() === 'GET';
        });
    }

    public function test_get_subscriber_parameters()
    {
        Http::fake();

        $this->client->getSubscriber(1);

        Http::assertSent(function (Request $request) {
            return Str::startsWith($request->url(), 'https://api.mailerlite.com/api/v2/subscribers/1') &&
                $request->method() === 'GET';
        });
    }

    public function test_create_subscriber_parameters()
    {
        Http::fake();

        $this->client->createSubscriber('test@test.com', 'Test', 'Nowhere');

        Http::assertSent(function (Request $request) {
            $body = json_decode($request->body(), true);

            return Str::startsWith($request->url(), 'https://api.mailerlite.com/api/v2/subscribers') &&
                data_get($body, 'email') === 'test@test.com' &&
                data_get($body, 'name') === 'Test' &&
                data_get($body, 'fields.country') === 'Nowhere' &&
                $request->method() === 'POST';
        });
    }

    public function test_update_subscriber_parameters()
    {
        Http::fake();

        $this->client->updateSubscriber(1, 'Test', 'Nowhere');

        Http::assertSent(function (Request $request) {
            $body = json_decode($request->body(), true);

            return Str::startsWith($request->url(), 'https://api.mailerlite.com/api/v2/subscribers/1') &&
                data_get($body, 'name') === 'Test' &&
                data_get($body, 'fields.country') === 'Nowhere' &&
                $request->method() === 'PUT';
        });
    }

    public function test_delete_subscriber_parameters()
    {
        Http::fake();

        $this->client->deleteSubscriber(1, 'Test', 'Nowhere');

        Http::assertSent(function (Request $request) {
            return Str::startsWith($request->url(), 'https://api.mailerlite.com/api/v2/subscribers/1') &&
                $request->method() === 'DELETE';
        });
    }
}
