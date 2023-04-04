<?php

namespace Tests\Feature;

use App\Mailerlite\ApiClient;
use App\Mailerlite\Error;
use App\Mailerlite\ErrorDetails;
use App\Mailerlite\Result;
use App\Mailerlite\Stats;
use App\Mailerlite\Record;
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
                "error" => [
                    'code' => 123,
                    'message' => 'Test',
                    'error_details' => [
                        "message" => 'Another test',
                        'errors' => [
                            'email' => 'Yet another test'
                        ],
                    ],
                ]
            ], 400);
        });

        $response = $this->mailerLite->getSubscribers();

        $this->assertInstanceOf(Error::class, $response);
        $this->assertEquals($response->code, 123);
        $this->assertEquals($response->message, 'Test');
        $this->assertInstanceOf(ErrorDetails::class, $response->details);
        $this->assertEquals($response->details->message, 'Another test');
        $this->assertEquals($response->details->errors, ['email' => 'Yet another test']);
    }

    public function test_get_subscribers_returns_result()
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

        $this->assertInstanceOf(Result::class, $response);
        $this->assertInstanceOf(Record::class, $response->records[0]);
        $this->assertEquals($response->records[0]->id, 1);
        $this->assertEquals($response->records[0]->email, 'test@test.com');
        $this->assertEquals($response->records[0]->name, 'Test');
        $this->assertEquals($response->records[0]->country, 'Nowhere');
        $this->assertEquals($response->records[0]->subscribeDate, '03-04-2023');
        $this->assertEquals($response->records[0]->subscribeTime, '22:16:37');
    }

    public function test_get_subscribers_handles_offset_with_limit()
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
                ],
                [
                    'id' => 2,
                    'email' => 'test@test.com',
                    'name' => 'Test',
                    'fields' => [
                        [
                            'key' => 'country',
                            'value' => 'Nowhere'
                        ],
                    ],
                    'date_subscribe' => '2023-04-03 22:16:37',
                ],
                [
                    'id' => 3,
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

        $response = $this->mailerLite->getSubscribers(null, 1, 1);

        $this->assertEquals($response->records[0]->id, 2);
    }

    public function test_get_subscribers_handles_offset_without_limit()
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
                ],
                [
                    'id' => 2,
                    'email' => 'test@test.com',
                    'name' => 'Test',
                    'fields' => [
                        [
                            'key' => 'country',
                            'value' => 'Nowhere'
                        ],
                    ],
                    'date_subscribe' => '2023-04-03 22:16:37',
                ],
                [
                    'id' => 3,
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

        $response = $this->mailerLite->getSubscribers(null, 1);

        $this->assertEquals($response->records[1]->id, 3);
    }

    public function test_create_subscriber_returns_result()
    {
        Http::fake(function ($request) {
            return Http::response([
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
            ], 200);
        });

        $response = $this->mailerLite->createSubscriber('test@test.com', 'Test', 'Nowhere');

        $this->assertInstanceOf(Result::class, $response);
        $this->assertEquals($response->count, 1);
        $this->assertInstanceOf(Record::class, $response->records[0]);
    }

    public function test_update_subscriber_returns_result()
    {
        Http::fake(function ($request) {
            return Http::response([
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
            ], 200);
        });

        $response = $this->mailerLite->updateSubscriber(1, 'Test', 'Nowhere');

        $this->assertInstanceOf(Result::class, $response);
        $this->assertEquals($response->count, 1);
        $this->assertInstanceOf(Record::class, $response->records[0]);
    }

    public function test_delete_subscriber_returns_result()
    {
        Http::fake(function ($request) {
            return Http::response('', 200);
        });

        $response = $this->mailerLite->deleteSubscriber(1);

        $this->assertInstanceOf(Result::class, $response);
        $this->assertEquals($response->count, 0);
        $this->assertEquals($response->records, []);
    }
}
