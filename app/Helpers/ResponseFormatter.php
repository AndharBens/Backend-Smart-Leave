<?php

namespace App\Helpers;

use Illuminate\Http\JsonResponse;

class ResponseFormatter
{
    /**
     * Struktur Response Standar
     * Menggunakan static property untuk efisiensi RAM.
     */
    protected static $response = [
        'meta' => [
            'code' => 200,
            'status' => 'success',
            'message' => null,
            'timestamp' => null,
        ],
        'data' => null,
    ];

    /**
     * =========================
     * SUCCESS RESPONSE
     * =========================
     */
    public static function success($data = null, ?string $message = null, int $code = 200): JsonResponse
    {
        self::$response['meta']['code'] = $code;
        self::$response['meta']['status'] = 'success'; // 🔥 Reset status (Penting!)
        self::$response['meta']['message'] = $message;
        self::$response['data'] = $data;

        return response()->json(self::$response, $code);
    }

    /**
     * =========================
     * ERROR RESPONSE
     * =========================
     */
    public static function error($data = null, ?string $message = null, int $code = 400): JsonResponse
    {
        self::$response['meta']['code'] = $code;
        self::$response['meta']['status'] = 'error';
        self::$response['meta']['message'] = $message;
        self::$response['data'] = $data;

        return response()->json(self::$response, $code);
    }

    /**
     * =========================
     * VALIDATION ERROR (422)
     * =========================
     */
    public static function validationError($errors, ?string $message = 'Validation failed'): JsonResponse
    {
        self::$response['meta']['code'] = 422;
        self::$response['meta']['status'] = 'error';
        self::$response['meta']['message'] = $message;
        self::$response['data'] = $errors;

        return response()->json(self::$response, 422);
    }

    /**
     * =========================
     * UNAUTHORIZED (401)
     * =========================
     */
    public static function unauthorized(?string $message = 'Unauthorized'): JsonResponse
    {
        self::$response['meta']['code'] = 401;
        self::$response['meta']['status'] = 'error';
        self::$response['meta']['message'] = $message;
        self::$response['data'] = null;

        return response()->json(self::$response, 401);
    }

    /**
     * =========================
     * FORBIDDEN (403)
     * =========================
     */
    public static function forbidden(?string $message = 'Forbidden'): JsonResponse
    {
        self::$response['meta']['code'] = 403;
        self::$response['meta']['status'] = 'error';
        self::$response['meta']['message'] = $message;
        self::$response['data'] = null;

        return response()->json(self::$response, 403);
    }

    /**
     * =========================
     * NOT FOUND (404)
     * =========================
     */
    public static function notFound(?string $message = 'Resource not found'): JsonResponse
    {
        self::$response['meta']['code'] = 404;
        self::$response['meta']['status'] = 'error';
        self::$response['meta']['message'] = $message;
        self::$response['data'] = null;

        return response()->json(self::$response, 404);
    }

    /**
     * =========================
     * SERVER ERROR (500)
     * =========================
     */
    public static function serverError(?string $message = 'Internal server error'): JsonResponse
    {
        self::$response['meta']['code'] = 500;
        self::$response['meta']['status'] = 'error';
        self::$response['meta']['message'] = $message;
        self::$response['data'] = null;

        return response()->json(self::$response, 500);
    }
}