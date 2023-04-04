<?php

namespace App\Providers;

use App\Mailerlite\MailerLiteClient;
use App\Services\ApiKeyService;
use App\Services\MailerLiteService;
use Illuminate\Support\ServiceProvider;

class MailerLiteServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->singleton(MailerLiteService::class, function ($app) {
            $apiClient = new MailerLiteClient(ApiKeyService::get());
            $mailerLite = new MailerLiteService($apiClient);

            return $mailerLite;
        });
    }
}
