<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Configuration of which driver/database to user for managing team member
    |--------------------------------------------------------------------------
    |
    | Here you may configure which driver to use for team management.
    | Also, individual configuration for each driver.
    | This is one bering in mind future scope.
    | So, if you want to use any other database provider than airtable.
    | You may write the driver and configure easily without breaking existing code
    |
    */

    'default' => 'airtable',
    'providers' => [
        'airtable' => [
            'api_key' => env('AIRTABLE_API_KEY', null),
            'table_endpoint' => env('AIRTABLE_TABLE_ENDPOINT', null)
        ]
    ]
];
