<?php

namespace App\Service\Delivery;

use App\Service\Delivery\DeliveryComputerInterface;
use App\Entity\Item;

class FarmitooDeliveryComputer implements DeliveryComputerInterface {

    public function getBrand(): string
    {
        return 'Farmitoo';
    }

    public function computeDelivery(array $items): int
    {
        return ceil(array_sum(array_map(fn(Item $item) => $item->getQuantity(), $items)) / 3) * 2000;
    }
}