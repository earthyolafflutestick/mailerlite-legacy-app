<?php

namespace Tests\Feature;

use App\Services\ApiKeyService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class ApiKeyServiceTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        DB::table(ApiKeyService::TABLE)->truncate();
    }

    public function test_it_encrypts()
    {
        ApiKeyService::set('test');

        $apiKey = DB::table(ApiKeyService::TABLE)->first()->value;

        $this->assertEquals(Crypt::decryptString($apiKey), 'test');
    }

    public function test_it_decrypts()
    {
        ApiKeyService::set('test');

        $this->assertEquals(ApiKeyService::get(), 'test');
    }

    public function test_it_always_overwrites()
    {
        ApiKeyService::set('first');
        ApiKeyService::set('last');

        $this->assertEquals(ApiKeyService::get(), 'last');
    }

    public function tearDown(): void
    {
        DB::table(ApiKeyService::TABLE)->truncate();

        parent::tearDown();
    }
}
