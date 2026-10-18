
<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Developer credit
    |--------------------------------------------------------------------------
    | Only enable these promotional details after the client approves them.
    */

    'enabled' => env('DEVELOPER_PROMOTION_ENABLED', false),

    'name' => env('DEVELOPER_NAME', 'Your Name'),

    // International format with digits only, e.g. 923001234567.
    'whatsapp_number' => env('DEVELOPER_WHATSAPP_NUMBER', ''),

    'whatsapp_message' => env(
        'DEVELOPER_WHATSAPP_MESSAGE',
        'Hello, I would like to discuss a website development project.'
    ),

    'headline' => 'Need a Website for Your Business?',

    'description' => 'I build responsive business websites, e-commerce stores, and custom admin dashboards.',

    'services' => [
        'Laravel Development',
        'E-commerce Websites',
        'Admin Dashboards',
        'Responsive Web Design',
    ],
];
