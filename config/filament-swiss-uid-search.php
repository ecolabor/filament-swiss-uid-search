<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Default Search Limit
    |--------------------------------------------------------------------------
    |
    | The maximum number of results to return when searching for companies.
    |
    */
    'search_limit' => env('FILAMENT_SWISS_UID_SEARCH_LIMIT', 50),

    /*
    |--------------------------------------------------------------------------
    | Show Additional Information
    |--------------------------------------------------------------------------
    |
    | Configure which additional information should be displayed in search
    | results.
    |
    */
    'show' => [
        'vat_number' => true,
        'legal_form' => true,
        'address' => true,
        'canton' => true,
    ],

    /*
    |--------------------------------------------------------------------------
    | Default Language
    |--------------------------------------------------------------------------
    |
    | The default language for API responses. Supported values:
    | 'de' (German), 'fr' (French), 'it' (Italian), 'en' (English)
    |
    */
    'language' => env('FILAMENT_SWISS_UID_LANGUAGE', 'de'),
];
