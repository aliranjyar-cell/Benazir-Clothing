<?php
return [
    'announcement' => 'A considered wardrobe, made for moments that matter.',
    'notifications' => [
        'admin_email' => env('STORE_ADMIN_EMAIL'),
    ],
    'home' => [
        'hero_eyebrow' => "{{ strtoupper(config('storefront.store_name', 'AURELLE')) }} · THE NEW EDIT",
        'hero_title' => 'Tradition, in a new light.',
        'hero_description' => 'Thoughtful South Asian dressing, shaped by craft and made to be worn your way.',
        'hero_cta' => 'Explore the collection',
        'story_eyebrow' => 'A study in craft',
        'story_title' => 'Made slowly. Worn beautifully.',
        'story_description' => 'We look to the detail: the hand, the fabric, the finish. Pieces with a sense of occasion, and room for your own expression.',
        'story_cta' => 'Discover our point of view',
        'newsletter_title' => "A note from {{ config('storefront.store_name', 'AURELLE') }}",
        'newsletter_description' => 'An occasional letter on new pieces, considered craft, and the art of getting dressed.',
    ],
    'checkout' => [
        'currency' => 'PKR',
        'payment_methods' => [
            'cash_on_delivery' => 'Cash on Delivery',
        ],
        'default_payment_method' => 'cash_on_delivery',
        'shipping_flat' => 0,
    ],
    'settings' => [
        'name' => 'AURELLE',
        'tagline' => 'A modern edit of heritage South Asian dressing.',
        'contact_email' => 'care@benazir.example',
        'admin_email' => env('STORE_ADMIN_EMAIL'),
        'phone' => '+92 300 1234567',
        'address' => 'Lahore, Pakistan',
        'instagram' => 'BenazirClothing',
    ],
];

