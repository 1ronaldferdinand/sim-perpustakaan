<?php

    namespace App\Helpers;

    class ApiFormatter{
        protected static $response = [
            'meta' => [
                'code' => null,
                'message' => null,
            ],
            'data' => [],
        ];

        public static function createJson ($code, $message, $data = []){
            self::$response['meta']['code'] = $code;
            self::$response['meta']['message'] = $message;
            self::$response['data'] = $data;

            return response()->json(self::$response, self::$response['meta']['code']);
        } 
    }
