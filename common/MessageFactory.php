<?php
/**
 * Created by PhpStorm.
 * User: arash
 * Date: 8/8/16
 * Time: 12:34 AM
 */

namespace Common;


use Exception;
use Illuminate\Http\JsonResponse;

class MessageFactory
{
    private static array $errors = [
        200 => 'green',
        400 => 'orange',
    ];

    public static function create(array $messages, int $code, mixed $data = [], bool $json = false): bool|array|string
    {
        try {
            $color = self::$errors[$code];
        } catch (Exception $e) {
            $color = 'red';
        }
        $result_messages = [];
        foreach ($messages as $message => $parameters) {
            if (gettype($parameters) === "array")
                $result_messages[] = trans($message, $parameters);
            else
                $result_messages[] = trans($parameters);
        }
        $response = [
            'transmission' => [
                'messages' => $result_messages,
                'color' => $color,
                'code' => $code
            ],
            'data' => $data
        ];
        return $json ? json_encode($response) : $response;
    }

    public static function jsonResponse(array $messages, int $code, mixed $data = [], bool $json = false): JsonResponse
    {
        return response()->json(self::create($messages, $code, $data, $json), $code);
    }

    public static function createWithValidationMessages(array $messages, int $code, mixed $data = [], bool $json = false): bool|array|string
    {
        $result_array = [];
        foreach ($messages as $key => $value) {
            $result_array[] = $value[0];
        }
        return self::create($result_array, $code, $data, $json);
    }
}
