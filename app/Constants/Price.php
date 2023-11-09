<?php

namespace App\Constants;

interface Price
{
    public const price = [
        1 => [
            'type' => 'free',
            'price_id' => '',
        ],
        2 => [
            'type' => 'standard',
            'price_id' => 'PRICE_STANDARD_ID'
        ],
        3 => [
            'type' => 'premium',
            'price_id' => 'PRICE_PREMIUM_ID'
        ],
    ];
}
