<?php
require_once 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();
\Setting::updateOrCreate(['id' => 1], [
    'store_name' => 'TestBrand', 'store_tagline' => 'A fresh start.',
    'contact_email' => 'care@test.example', 'contact_phone' => '+92 300 0000000',
    'address' => 'Karachi, Pakistan', 'instagram' => 'TestBrandPK',
    'currency' => 'PKR', 'timezone' => 'Asia/Karachi',
]);
echo 'DB_NAME: ' . config('storefront.store_name') . PHP_EOL;
echo 'DB_TAGLINE: ' . config('storefront.store_tagline') . PHP_EOL;
echo 'DB_ADMIN_EMAIL: ' . config('storefront.notifications.admin_email') . PHP_EOL;
echo 'APP_NAME: ' . config('app.name') . PHP_EOL;
echo 'MAIL_FROM_NAME: ' . config('mail.from.name') . PHP_EOL;
echo 'CONTACT: ' . json_encode(config('storefront.contact')) . PHP_EOL;
echo 'HOME_EBROW: ' . config('storefront.home.hero_eyebrow') . PHP_EOL;
echo 'HOME_NTITLE: ' . config('storefront.home.newsletter_title') . PHP_EOL;
echo 'FULL_OK' . PHP_EOL;
