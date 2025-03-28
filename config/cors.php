<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Cross-Origin Resource Sharing (CORS) Configuration
    |--------------------------------------------------------------------------
    |
    | Here you may configure your settings for cross-origin resource sharing
    | or "CORS". This determines what cross-origin operations may execute
    | in web browsers. You are free to adjust these settings as needed.
    |
    | To learn more: https://developer.mozilla.org/en-US/docs/Web/HTTP/CORS
    |
    */

    'paths' => ['api/*'], // Allow CORS on API routes
    'allowed_methods' => ['*'], // Allow all HTTP methods (GET, POST, etc.)
    'allowed_origins' => ['http://localhost:3000'], // Frontend origin
    'allowed_origins_patterns' => [], // Regex patterns for origins (optional)
    'allowed_headers' => ['*'], // Allow all headers
    'exposed_headers' => [], // Expose headers to the client (optional)
    'max_age' => 0, // Cache duration (optional)
    'supports_credentials' => false, // Set to true if using cookies/auth

];
