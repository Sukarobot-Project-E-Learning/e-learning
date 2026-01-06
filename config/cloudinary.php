<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Cloudinary Configuration
    |--------------------------------------------------------------------------
    |
    | These values are used for uploading certificate templates and generating
    | certificate images with text overlay via Cloudinary API.
    |
    */

    'cloud_name' => env('CLOUDINARY_CLOUD_NAME', ''),
    'api_key' => env('CLOUDINARY_API_KEY', ''),
    'api_secret' => env('CLOUDINARY_API_SECRET', ''),

    /*
    |--------------------------------------------------------------------------
    | Upload Settings
    |--------------------------------------------------------------------------
    */

    'upload_preset' => env('CLOUDINARY_UPLOAD_PRESET', null),
    'folder' => 'certificates/templates',

    /*
    |--------------------------------------------------------------------------
    | Default Font Settings for Certificate
    |--------------------------------------------------------------------------
    */

    'fonts' => [
        'name' => 'Lobster',      // Font for recipient name
        'text' => 'Lato',         // Font for other text (number, description, date)
    ],

    'colors' => [
        'text' => 'rgb:191919',   // Dark gray text color
    ],
];
