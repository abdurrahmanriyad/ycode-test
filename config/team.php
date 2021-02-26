<?php

return [
    'default' => 'airtable',
    'providers' => [
        'airtable' => [
            'api_key' => env('AIRTABLE_API_KEY', null)
        ]
    ]
];
