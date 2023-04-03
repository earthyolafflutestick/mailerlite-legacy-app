<?php

namespace App\Services;

use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;

class ApiKeyService
{
    public const TABLE = 'api_keys';

    public static function reset()
    {
        DB::table(self::TABLE)->truncate();
    }

    public static function set($value)
    {
        self::reset();

        DB::table(self::TABLE)->insert([
            'value' => Crypt::encryptString($value),
        ]);
    }

    public static function get()
    {
        if (DB::table(self::TABLE)->count() === 0) {
            return null;
        }

        $apiKey = DB::table(self::TABLE)->first()->value;

        return Crypt::decryptString($apiKey);
    }
}
