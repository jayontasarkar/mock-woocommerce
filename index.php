<?php

require 'vendor/autoload.php';

use Faker\Factory;
use Automattic\WooCommerce\Client;

$faker = Factory::create();

$woocommerce = new Client(
    'http://wordpress2.test/',
    'ck_8087c38ae0efc384fae061561704cd6c2fa50553',
    'cs_2ccc7281517aa171368e9c0627af0974d57bee37',
    [
        'version' => 'wc/v3',
    ]
);

/**
 * Fake Products Generator
 */
// for ($i=1; $i < 40; $i++) {
//     $price = $faker->numberBetween(50, 200);
//     $payload = [
//         'name' => $faker->sentence(4),
//         'type' => 'simple',
//         'regular_price' => "{$price}",
//         'description' => $faker->sentence(40),
//         'short_description' => $faker->sentence(10)
//     ];

//     $woocommerce->post('products', $payload);
// }

/**
 * Orders Generator
 */
// Get All Products
$products = $woocommerce->get('products', ['per_page' => 100]);
$productIds = [];
foreach ($products as $product) {
    array_push($productIds, $product->id);
}

for ($i=1; $i < 1000; $i++) {
    $data = [
        'payment_method' => 'cod',
        'payment_method_title' => 'Cash on Delivery',
        'set_paid' => $i % 2 === 0 ? true : false,
        'billing' => [
            'first_name' => $fname = $faker->firstName,
            'last_name' => $lname = $faker->lastName,
            'address_1' => $address = $faker->address,
            'address_2' => '',
            'city' => $city = $faker->city,
            'state' => $state = $faker->state,
            'postcode' => $postcode = $faker->postcode,
            'country' => $country = $faker->countryCode,
            'email' => $email = $faker->safeEmail,
            'phone' => $phone = $faker->phoneNumber
        ],
        'shipping' => [
            'first_name' => $fname,
            'last_name' => $lname,
            'address_1' => $address,
            'address_2' => '',
            'city' => $city,
            'state' => $state,
            'postcode' => $postcode,
            'country' => $country
        ],
        'line_items' => [
            [
                'product_id' => $faker->randomElement(array_values($productIds)),
                'quantity' => $faker->numberBetween(1, 10)
            ]
        ]
    ];
    $woocommerce->post('orders', $data);
}
return 'Done';
