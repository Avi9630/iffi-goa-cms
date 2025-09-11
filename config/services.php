<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'example_api' => [
        'upload_image_base_url' => env('UPLOAD_IMAGE_BASE_URL'),
        'image_list_base_url' => env('IMAGE_LIST_BASE_URL'),
        'get_image_by_name_base_url' => env('IMAGE_BY_NAME_URL'),
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'resend' => [
        'key' => env('RESEND_KEY'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

    'gcs' => [
        'project_id' => env('GOOGLE_CLOUD_PROJECT_ID'),
        'key_file' => storage_path('app/keys/' . env('GOOGLE_APPLICATION_CREDENTIALS')),
        'bucket' => env('GOOGLE_CLOUD_STORAGE_BUCKET'),
        'public_url_format' => 'https://storage.googleapis.com/%s/%s',
    ],
];
