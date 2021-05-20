<?php

namespace App\Service;

use App\Entity\Item;
use App\Service\Delivery\DeliveryComputerInterface;
use Exception;

class DeliveryService {

    protected array $deliveryComputers;

    public function __construct(iterable $deliveryComputers)
    {

        $this->deliveryComputers = [];
        /** @var DeliveryComputerInterface $deliveryComputer */
        foreach($deliveryComputers as $deliveryComputer) {
            $this->deliveryComputers[$deliveryComputer->getBrand()] = $deliveryComputer;
        }
    }

    /**
     * @throw Exception
     */
    public function computeDeliveries(array $items): array
    {
        $sortedItems = array_reduce($items, function ($carry, Item $item) {
            $brand = $item->getProduct()->getBrand();
            $carry[$brand] = array_merge(
                $carry[$brand] ?? [],
                [$item]
            );
            return $carry;
        }, []);
        $deliveries = [];

        foreach ($sortedItems as $brand => $items) {
            if(array_key_exists($brand, $this->deliveryComputers)) {
                $deliveries[$brand] = $this->deliveryComputers[$brand]->computeDelivery($items);
            } else {
                throw new Exception('Unknow delivery for brand '.$brand);
            }
        }
        return $deliveries;
    }
}