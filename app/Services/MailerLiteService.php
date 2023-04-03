<?php

namespace App\Services;

use App\Mailerlite\ApiClient;
use App\Mailerlite\Error;
use App\Mailerlite\ErrorDetails;
use App\Mailerlite\Subscriber;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Arr;

class MailerLiteService
{
    private $client;

    public function __construct(ApiClient $client)
    {
        $this->client = $client;
    }

    public function getSubscribers($offset = 0, $limit = 10)
    {
        $closure = function () use ($offset, $limit) {
            return $this->client->getSubscribers($offset, $limit);
        };

        return $this->makeRequest($closure, true);
    }

    public function searchSubscribers($query, $offset = 0, $limit = 10)
    {
        $closure = function () use ($query, $offset, $limit) {
            return $this->client->searchSubscribers($query, $offset, $limit);
        };

        return $this->makeRequest($closure, true);
    }

    public function createSubscriber($email, $name = '', $country = '')
    {
        $closure = function () use ($email, $name, $country) {
            return $this->client->createSubscriber($email, $name, $country);
        };

        return $this->makeRequest($closure, false);
    }

    public function updateSubscriber($id_or_email, $name = '', $country = '')
    {
        $closure = function () use ($id_or_email, $name, $country) {
            return $this->client->updateSubscriber($id_or_email, $name, $country);
        };

        return $this->makeRequest($closure, false);
    }

    private function makeRequest(\Closure $closure, $multiple = false)
    {
        try {
            $response = $closure();

            if ($response->failed()) {
                return $this->wrapError($response);
            }

            return $this->wrapResult($response, $multiple);
        } catch (\Exception $e) {
            return $this->wrapException();
        }
    }

    private function wrapException()
    {
        $message = __('mailerlite.messages.500');

        return new Error($message);
    }

    private function wrapError(Response $response)
    {
        $code = $response->status();
        $json = $response->json();
        $message = Arr::get($json, 'message', __('mailerlite.messages.500'));
        $error = new Error($message, null, $code);

        if (Arr::has($json, 'error_details')) {
            $message = Arr::get($json, 'error_details.message', '');
            $errors = Arr::get($json, 'error_details.errors', []);
            $errorDetails = new ErrorDetails($message, $errors);

            $error->details = $errorDetails;
        }

        return $error;
    }

    private function wrapResult(Response $response, $multiple = false)
    {
        $json = $response->json();
        $json = $multiple ? $json : [$json];

        $subscribers = array_map(function ($item) {
            $subscribe_datetime = \DateTime::createFromFormat('Y-m-d H:i:s', $item['date_subscribe']);
            $subscribe_date = $subscribe_datetime->format('d-m-Y');
            $subscribe_time = $subscribe_datetime->format('H:i:s');
            $country = Arr::first($item['fields'], fn($f) => $f['key'] === 'country')['value'];

            return new Subscriber(
                $item['id'],
                $item['email'],
                $item['name'],
                $country,
                $subscribe_date,
                $subscribe_time
            );
        }, $json);

        if (!$multiple && count($subscribers) > 0) {
            return $subscribers[0];
        }

        return $subscribers;
    }
}
