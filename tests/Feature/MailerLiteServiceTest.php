<?php

namespace Tests\Feature;

use App\Mailerlite\ApiClient;
use App\Mailerlite\Error;
use App\Mailerlite\ErrorDetails;
use App\Mailerlite\Subscriber;
use App\Services\MailerLiteService;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class MailerLiteServiceTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        $apiClient = new ApiClient('');
        $this->mailerLite = new MailerLiteService($apiClient);
    }

    public function test_it_handles_connection_errors()
    {
        Http::fake(function ($request) {
            throw new ConnectionException();
        });

        $response = $this->mailerLite->getSubscribers();

        $this->assertInstanceOf(Error::class, $response);
        $this->assertEquals($response->code, 500);
        $this->assertEquals($response->message, __('mailerlite.messages.500'));
    }

    public function test_it_handles_api_errors()
    {
        Http::fake(function ($request) {
            return Http::response([
                'message' => 'Test',
                'error_details' => [
                    "message" => 'Another test',
                    'errors' => [
                        'email' => 'Yet another test'
                    ],
                ],
            ], 400);
        });

        $response = $this->mailerLite->getSubscribers();

        $this->assertInstanceOf(Error::class, $response);
        $this->assertEquals($response->code, 400);
        $this->assertEquals($response->message, 'Test');
        $this->assertInstanceOf(ErrorDetails::class, $response->details);
        $this->assertEquals($response->details->message, 'Another test');
        $this->assertEquals($response->details->errors, ['email' => 'Yet another test']);
    }

    public function test_get_subscribers_returns_array_of_subscribers()
    {
        Http::fake(function ($request) {
            return Http::response([
                [
                    'id' => 1,
                    'email' => 'test@test.com',
                    'name' => 'Test',
                    'fields' => [
                        [
                            'key' => 'country',
                            'value' => 'Nowhere'
                        ],
                    ],
                    'date_subscribe' => '2023-04-03 22:16:37',
                ]
            ], 200);
        });

        $response = $this->mailerLite->getSubscribers();

        $this->assertIsArray($response);
        $this->assertInstanceOf(Subscriber::class, $response[0]);
        $this->assertEquals($response[0]->id, 1);
        $this->assertEquals($response[0]->email, 'test@test.com');
        $this->assertEquals($response[0]->name, 'Test');
        $this->assertEquals($response[0]->country, 'Nowhere');
        $this->assertEquals($response[0]->subscribeDate, '03-04-2023');
        $this->assertEquals($response[0]->subscribeTime, '22:16:37');
    }

    public function test_search_subscribers_returns_array_of_subscribers()
    {
        Http::fake(function ($request) {
            return Http::response([
                [
                    'id' => 1,
                    'email' => 'test@test.com',
                    'name' => 'Test',
                    'fields' => [
                        [
                            'key' => 'country',
                            'value' => 'Nowhere'
                        ],
                    ],
                    'date_subscribe' => '2023-04-03 22:16:37',
                ]
            ], 200);
        });

        $response = $this->mailerLite->searchSubscribers('test@test.com');

        $this->assertIsArray($response);
        $this->assertInstanceOf(Subscriber::class, $response[0]);
        $this->assertEquals($response[0]->id, 1);
        $this->assertEquals($response[0]->email, 'test@test.com');
        $this->assertEquals($response[0]->name, 'Test');
        $this->assertEquals($response[0]->country, 'Nowhere');
        $this->assertEquals($response[0]->subscribeDate, '03-04-2023');
        $this->assertEquals($response[0]->subscribeTime, '22:16:37');
    }
}
