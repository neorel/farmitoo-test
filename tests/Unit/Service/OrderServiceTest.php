<?php

namespace App\Tests\Service;

use App\Entity\Item;
use App\Entity\Order;
use App\Entity\Product;
use App\Service\OrderService;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class OrderServiceTest extends KernelTestCase
{

    private OrderService $orderService;

    protected function setUp(): void
    {
        self::bootKernel();
        $this->orderService = self::$container->get('App\Service\OrderService');
    }

    public function testOrderWithoutPromotion(): void
    {
        $order = new Order();

        $order->addProduct(new Product('test1', 1500, 'Farmitoo'), 4);

        $computedOrder = $this->orderService->computeOrder($order);

        $this->assertEquals(6000, $computedOrder['total_items']);
        $this->assertEmpty($computedOrder['promotions']);
        $this->assertEquals(10000, $computedOrder['total_tax_excl']);
        $this->assertEquals(11200, $computedOrder['total_tax_incl']);
    }

    public function testOrderWithPromotion(): void
    {
        $order = new Order();

        $order->addProduct(new Product('test1', 3000, 'Farmitoo'), 12);
        $order->addProduct(new Product('test2', 4500, 'Farmitoo'), 2);
        $order->addProduct(new Product('test3', 1500, 'Gallagher'), 4);

        $computedOrder = $this->orderService->computeOrder($order);

        $this->assertEquals(51000, $computedOrder['total_items']);
        $this->assertEquals(1, count($computedOrder['promotions']));
        $this->assertEquals(4080, $computedOrder['promotions'][0]['value']);
        $this->assertEquals(58420, $computedOrder['total_tax_excl']);
        $this->assertEquals(66976, $computedOrder['total_tax_incl']);
    }
}