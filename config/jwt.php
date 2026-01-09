<?php

return [
    /*
    |--------------------------------------------------------------------------
    | JWT Secret Key
    |--------------------------------------------------------------------------
    |
    | Secret key for signing JWT tokens. Uses APP_KEY by default.
    |
    */
    'secret' => env('JWT_SECRET', env('APP_KEY')),

    /*
    |--------------------------------------------------------------------------
    | JWT Token Expiry
    |--------------------------------------------------------------------------
    |
    | Token expiry time in seconds. Default is 24 hours (86400 seconds).
    |
    */
    'expires_in' => env('JWT_EXPIRES_IN', 86400),

    /*
    |--------------------------------------------------------------------------
    | Refresh Token Expiry
    |--------------------------------------------------------------------------
    |
    | Refresh token expiry time in seconds. Default is 7 days.
    |
    */
    'refresh_expires_in' => env('JWT_REFRESH_EXPIRES_IN', 604800),

    /*
    |--------------------------------------------------------------------------
    | Algorithm
    |--------------------------------------------------------------------------
    |
    | The algorithm used for signing the token.
    |
    */
    'algorithm' => env('JWT_ALGORITHM', 'HS256'),
];
