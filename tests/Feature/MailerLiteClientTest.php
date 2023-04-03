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
            return Str::startsWith($request->url(), 'https://api.mailerlite.com') &&
                data_get($request->data(), 'offset') === 10 &&
                data_get($request->data(), 'limit') === 20 &&
                $request->method() === 'GET';
        });
    }
}
