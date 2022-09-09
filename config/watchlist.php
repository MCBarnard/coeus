<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Products
    |--------------------------------------------------------------------------
    |
    | Our products that we want to keep an eye out for
    |
    */

    'products-test' => [
        'milo' => [
            'checkers' => [
                'exact-item-names' => 'Nestle Milo Tin 500g',
                'product-x-path' => [
                    'method' => 'xpath',
                    'path' => ' //a[text()[contains(., " Nestlé Milo Original Breakfast Energy Drink 500g")]]'
                ],
            ],
            'pnp' => [
                'exact-item-names' => 'Nestle Milo Tin 500g',
                'product-x-path' => [
                    'method' => 'xpath',
                    'path' => '//*[@id="productCarouselItemContainer_000000000000163262_EA"]/div/div[3]/a'
                ],
            ],
            'game' => [
                'exact-item-names' => '480258',
                'product-x-path' => [
                'method' => 'xpath',
                    'path' => ' //div[text()[contains(., "Nestle Milo Chocolate Malt Drink Tin 500 G")]]'
                ],
            ],
            'shoprite' => [
                'exact-item-names' => 'Nestlé Milo Original Breakfast Energy Drink 500g',
                'product-x-path' => [
                'method' => 'xpath',
                    'path' => '//a[text()[contains(., " Nestlé Milo Original Breakfast Energy Drink 500g")]]'
                ],
            ],
            'woolworths' => [
                'exact-item-names' => '20002480',
                'product-x-path' => [
                'method' => 'xpath',
                    'path' => '//a[text()[contains(., " Baked Beans 410 g")]]'
                ],
            ],
            'barcode' => '6001068480401',
            'common-name' => 'Milo',
            'slug' => 'milo'
        ],
    ],

    'products' => [
        'coke' => [
            'checkers' => [
                'exact-item-names' => 'Coca-Cola Original Less Sugar Soft Drink Bottle 2L',
                'product-x-path' => [
                    'method' => 'xpath',
                    'path' => ' //a[text()[contains(., "Coca-Cola Original Less Sugar Soft Drink Bottle 2L")]]'
                ],
            ],
            'pnp' => [
                'exact-item-names' => 'Coca-cola Plastic 2l',
                'product-x-path' => [
                    'method' => 'xpath',
                    'path' => '//*[@id="productCarouselItemContainer_000000000000312875_EA"]/div/div[3]/a'
                ],
            ],
            'barcode' => '5449000009067',
            'common-name' => 'Coca Cola 2L',
            'slug' => 'coke',
        ],
        'twin-saver-toilet-paper' => [
            'checkers' => [
                'exact-item-names' => 'Twinsaver Luxury White Twin Ply Toilet Paper 18 Pack',
                'product-x-path' => [
                    'method' => 'xpath',
                    'path' => ' //a[text()[contains(., " Twinsaver Luxury White Twin Ply Toilet Paper 18 Pack")]]'
                ],
            ],
            'pnp' => [
                'exact-item-names' => 'Twinsaver Luxury 2 Ply Toilet Paper 18s',
                'product-x-path' => [
                    'method' => 'xpath',
                    'path' => '//*[@id="productCarouselItemContainer_000000000000327958_EA"]/div/div[3]/a'
                ],
            ],
            'barcode' => '6001070113298',
            'common-name' => 'Toilet Paper',
            'slug' => 'toilet-paper'
        ],
        'no-name-toilet-paper' => [
            'checkers' => [
                'exact-item-names' => 'Checkers Housebrand 2 Ply Toilet Rolls 18 Pack',
                'product-x-path' => [
                    'method' => 'xpath',
                    'path' => ' //a[text()[contains(., " Checkers Housebrand 2 Ply Toilet Rolls 18 Pack")]]'
                ],
            ],
            'pnp' => [
                'exact-item-names' => 'PnP Toilet Paper 2 Ply White 18s',
                'product-x-path' => [
                    'method' => 'xpath',
                    'path' => '//*[@id="productCarouselItemContainer_000000000000359011_EA"]/div/div[3]/a'
                ],
            ],
            'barcode' => '6001001825054',
            'common-name' => 'Toilet Paper',
            'slug' => 'toilet-paper'
        ],
        'baby-soft-toilet-paper' => [
            'checkers' => [
                'exact-item-names' => 'Baby Soft White 2 Ply Toilet Rolls 18 Pack',
                'product-x-path' => [
                    'method' => 'xpath',
                    'path' => ' //a[text()[contains(., " Baby Soft White 2 Ply Toilet Rolls 18 Pack")]]'
                ],
            ],
            'pnp' => [
                'exact-item-names' => 'Baby Soft 2 Ply Toilet Paper White 18s',
                'product-x-path' => [
                    'method' => 'xpath',
                    'path' => '//*[@id="productCarouselItemContainer_000000000000215170_EA"]/div/div[3]/a'
                ],
            ],
            'barcode' => '6001019000252',
            'common-name' => 'Toilet Paper',
            'slug' => 'toilet-paper'
        ],
        'milo' => [
            'checkers' => [
                'exact-item-names' => 'Baby Soft White 2 Ply Toilet Rolls 18 Pack',
                'product-x-path' => [
                    'method' => 'xpath',
                    'path' => ' //a[text()[contains(., " Baby Soft White 2 Ply Toilet Rolls 18 Pack")]]'
                ],
            ],
            'pnp' => [
                'exact-item-names' => 'Nestle Milo Tin 500g',
                'product-x-path' => [
                'method' => 'xpath',
                    'path' => '//*[@id="productCarouselItemContainer_000000000000163262_EA"]/div/div[3]/a'
                ],
            ],
            'barcode' => '6001068480401',
            'common-name' => 'Milo',
            'slug' => 'milo'
        ],
    ],
];
