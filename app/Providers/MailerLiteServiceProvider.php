<?php

namespace App\Providers;

use App\Mailerlite\ApiClient;
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
            $apiClient = new ApiClient(config('mailerlite.apikey'));
            $mailerLite = new MailerLiteService($apiClient);

            return $mailerLite;
        });
    }
}