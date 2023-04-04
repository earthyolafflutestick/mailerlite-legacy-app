<?php

namespace Tests\Feature;

use App\Mailerlite\MailerLiteClient;
use App\Services\ApiKeyService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class ResponsesTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        ApiKeyService::set('test');
    }

    public function test_subscribers_index_displays_api_errors()
    {
        Http::fake([
            MailerLiteClient::BASE_URL . '/*' => Http::response([
                "error" => [
                    'code' => 123,
                    'message' => 'API error',
                    'error_details' => [
                        "message" => 'Another test',
                        'errors' => [
                            'email' => 'Yet another test'
                        ],
                    ],
                ]
            ], 400),
        ]);

        $response = $this->get('/subscribers');

        $response->assertSee('API error');

        $response = $this->getJson('/subscribers');

        $response->assertJson([
            'error' => 'API error',
        ]);
    }
}
