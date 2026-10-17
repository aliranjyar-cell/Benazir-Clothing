<?php

return [
    'announcement' => 'A considered wardrobe, made for moments that matter.',
    'notifications' => [
        'admin_email' => env('STORE_ADMIN_EMAIL'),
    ],
    'home' => [
        'hero_eyebrow' => 'AURELLE · THE NEW EDIT',
        'hero_title' => 'Tradition, in a new light.',
        'hero_description' => 'Thoughtful South Asian dressing, shaped by craft and made to be worn your way.',
        'hero_cta' => 'Explore the collection',
        'story_eyebrow' => 'A study in craft',
        'story_title' => 'Made slowly. Worn beautifully.',
        'story_description' => 'We look to the detail: the hand, the fabric, the finish. Pieces with a sense of occasion, and room for your own expression.',
        'story_cta' => 'Discover our point of view',
        'newsletter_title' => 'A note from AURELLE',
        'newsletter_description' => 'An occasional letter on new pieces, considered craft, and the art of getting dressed.',
    ],

    /*
    |--------------------------------------------------------------------------
    | Storefront checkout
    |--------------------------------------------------------------------------
    | Only payment methods listed here are accepted by the checkout
    | controller. There is no online payment gateway configured for this
    | project, so cash on delivery is the sole supported method and orders
    | are always created as "unpaid" until verified through the admin panel.
    */
    'checkout' => [
        'currency' => 'PKR',
        'payment_methods' => [
            'cash_on_delivery' => 'Cash on Delivery',
        ],
        'default_payment_method' => 'cash_on_delivery',
        'shipping_flat' => 0,
    ],
];
