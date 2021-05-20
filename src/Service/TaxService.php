<?php

namespace App\Service;

use App\Entity\Item;

class TaxService {

    protected static $TAX_RATES = [
        'Farmitoo' => [
            'FR' => 20,
        ],
        'Gallagher' => [
            'FR' => 5,
        ]
    ];

    public function computeTaxes(array $items): array
    {
        return array_reduce(
            $items,
            function(array $carry, Item $item) {
                $brand = $item->getProduct()->getBrand();
                $rate = self::$TAX_RATES[$brand]['FR'];
                $oldValue = array_key_exists($brand, $carry) ? $carry[$brand]['value'] : 0;
                $carry[$brand] = [
                    'rate' => $rate, 
                    'value' => $oldValue + (($rate / 100) * $item->getProduct()->getPrice() * $item->getQuantity())
                ];
                return $carry;
            },
            []
        );
    }
}