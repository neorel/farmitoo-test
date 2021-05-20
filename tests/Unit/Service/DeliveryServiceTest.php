<?php

namespace App\Tests\Unit\Service;

use App\Entity\Item;
use App\Entity\Product;
use App\Service\DeliveryService;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class DeliveryServiceTest extends KernelTestCase
{

    private DeliveryService $deliveryService;

    protected function setUp(): void
    {
        self::bootKernel();
        $this->deliveryService = self::$container->get('App\Service\DeliveryService');
    }

    public function testFarmitoo(): void
    {
        $deliveries = $this->deliveryService->computeDeliveries([
            new Item(new Product('test1', 200, 'Farmitoo'), 1)
        ]);

        $this->assertEquals(2000, $deliveries['Farmitoo']);

        $deliveries = $this->deliveryService->computeDeliveries([
            new Item(new Product('test1', 200, 'Farmitoo'), 3)
        ]);

        $this->assertEquals(2000, $deliveries['Farmitoo']);

        $deliveries = $this->deliveryService->computeDeliveries([
            new Item(new Product('test1', 200, 'Farmitoo'), 2),
            new Item(new Product('test2', 400, 'Farmitoo'), 3),
        ]);

        $this->assertEquals(4000, $deliveries['Farmitoo']);
    }

    public function testGallagher(): void
    {
        $deliveries = $this->deliveryService->computeDeliveries([
            new Item(new Product('test1', 200, 'Gallagher'), 1)
        ]);

        $this->assertEquals(1500, $deliveries['Gallagher']);

        $deliveries = $this->deliveryService->computeDeliveries([
            new Item(new Product('test1', 200, 'Gallagher'), 3)
        ]);

        $this->assertEquals(1500, $deliveries['Gallagher']);

        $deliveries = $this->deliveryService->computeDeliveries([
            new Item(new Product('test1', 200, 'Gallagher'), 2),
            new Item(new Product('test2', 400, 'Gallagher'), 3),
        ]);

        $this->assertEquals(1500, $deliveries['Gallagher']);
    }

    public function testMultipleBrands(): void
    {
        $deliveries = $this->deliveryService->computeDeliveries([
            new Item(new Product('test1', 200, 'Farmitoo'), 7),
            new Item(new Product('test2', 400, 'Farmitoo'), 3),
            new Item(new Product('test3', 1500, 'Gallagher'), 4),
            new Item(new Product('test4', 600, 'Gallagher'), 9),
        ]);

        $this->assertEquals(1500, $deliveries['Gallagher']);
        $this->assertEquals(8000, $deliveries['Farmitoo']);
    }

}