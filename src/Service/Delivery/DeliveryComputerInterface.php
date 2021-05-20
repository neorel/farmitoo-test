<?php

namespace App\Service\Delivery;

interface DeliveryComputerInterface {
    public function getBrand(): string;
    public function computeDelivery(array $items): int;
}