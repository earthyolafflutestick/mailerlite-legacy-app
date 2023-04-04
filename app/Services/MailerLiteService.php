<?php

namespace App\Services;

use App\Mailerlite\ApiClient;
use App\Mailerlite\Error;
use App\Mailerlite\ErrorDetails;
use App\Mailerlite\Result;
use App\Mailerlite\Stats;
use App\Mailerlite\Record;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class MailerLiteService
{
    private $client;

    public function __construct(ApiClient $client)
    {
        $this->client = $client;
    }

    public function getClient()
    {
        return $this->client;
    }

    public function getSubscribers($query = null, $offset = 0, $limit = null)
    {
        try {
            $response = $this->client->getSubscribers();

            if ($response->failed()) {
                return $this->wrapError($response);
            }

            $records = $response->json() ?? [];
            $records = $this->wrapRecords($records);

            if ($query) {
                $records = Arr::where($records, fn($record) => Str::contains($record->email, $query));
            }

            $count = count($records);
            $records = $limit !== null ?
                array_slice($records, $offset, $limit) :
                array_slice($records, $offset);

            return new Result($count, $records);
        } catch (\Exception $e) {
            return $this->wrapException();
        }
    }

    public function createSubscriber($email, $name = '', $country = '')
    {
        try {
            $response = $this->client->createSubscriber($email, $name, $country);

            if ($response->failed()) {
                return $this->wrapError($response);
            }

            $records = $response->json();
            $records = $records ? [$records] : [];
            $records = $this->wrapRecords($records);
            $count = count($records);

            return new Result($count, $records);
        } catch (\Exception $e) {
            return $this->wrapException();
        }
    }

    public function updateSubscriber($id_or_email, $name = '', $country = '')
    {
        try {
            $response = $this->client->updateSubscriber($id_or_email, $name, $country);

            if ($response->failed()) {
                return $this->wrapError($response);
            }

            $records = $response->json();
            $records = $records ? [$records] : [];
            $records = $this->wrapRecords($records);
            $count = count($records);

            return new Result($count, $records);
        } catch (\Exception $e) {
            return $this->wrapException();
        }
    }

    public function deleteSubscriber($id_or_email)
    {
        try {
            $response = $this->client->deleteSubscriber($id_or_email);

            if ($response->failed()) {
                return $this->wrapError($response);
            }

            return new Result(0, []);
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
        $json = $response->json();
        $code = Arr::get($json, 'error.code', 500);
        $message = Arr::get($json, 'error.message', __('mailerlite.messages.500'));
        $error = new Error($message, null, $code);

        if (Arr::has($json, 'error.error_details')) {
            $message = Arr::get($json, 'error.error_details.message', '');
            $errors = Arr::get($json, 'error.error_details.errors', []);
            $errorDetails = new ErrorDetails($message, $errors);

            $error->details = $errorDetails;
        }

        return $error;
    }

    private function wrapRecords($records)
    {
        $records = array_map(function ($record) {
            $subscribe_datetime = \DateTime::createFromFormat('Y-m-d H:i:s', $record['date_subscribe']);
            $subscribe_date = $subscribe_datetime->format('d-m-Y');
            $subscribe_time = $subscribe_datetime->format('H:i:s');
            $country = Arr::first($record['fields'], fn($f) => $f['key'] === 'country')['value'];

            return new Record(
                $record['id'],
                $record['email'],
                $record['name'],
                $country,
                $subscribe_date,
                $subscribe_time
            );
        }, $records);

        return $records;
    }
}
