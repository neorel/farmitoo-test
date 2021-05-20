<?php

namespace App\Service\Delivery;

use App\Service\Delivery\DeliveryComputerInterface;

class GallagherDeliveryComputer implements DeliveryComputerInterface {

    public function getBrand(): string
    {
        return 'Gallagher';
    }

    public function computeDelivery(array $items): int
    {
        return 1500;
    }
}
