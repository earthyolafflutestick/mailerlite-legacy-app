<?php

namespace App\Http\Controllers;

use App\Mailerlite\Error;
use App\Services\ApiKeyService;
use App\Services\MailerLiteService;
use Illuminate\Http\Request;

class ApiKeyController extends Controller
{
    public function create()
    {
        return view('apikeys.create');
    }

    public function store(Request $request, MailerLiteService $mailerLite)
    {
        $validated = $request->validate([
            'api_key' => [
                'required',
                function ($attribute, $value, $fail) use ($mailerLite) {
                    $mailerLite->getClient()->setApiKey($value);
                    $response = $mailerLite->getSubscribers();

                    if ($response instanceof Error) {
                        $fail($response->message);
                    }
                }
            ],
        ]);

        ApiKeyService::set($validated['api_key']);

        return redirect()->route('subscribers.index');
    }
}
