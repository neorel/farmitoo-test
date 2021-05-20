<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class PriceExtension extends AbstractExtension
{
    public function getFilters()
    {
        return [
            new TwigFilter('price', [$this, 'formatPrice']),
        ];
    }

    public function formatPrice($number, $decimals = 2, $decPoint = ',', $thousandsSep = ' ')
    {
        $price = number_format($number / 100, $decimals, $decPoint, $thousandsSep);
        $price = $price.' €';

        return $price;
    }
}