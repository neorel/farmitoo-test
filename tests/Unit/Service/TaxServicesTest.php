<?php

namespace App\Tests\Service;

use App\Entity\Item;
use App\Entity\Product;
use App\Service\TaxService;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class TaxServiceTest extends KernelTestCase
{

    private TaxService $taxService;

    protected function setUp(): void
    {
        self::bootKernel();
        $this->taxService = self::$container->get('App\Service\TaxService');
    }

    public function testTaxes(): void
    {
        $taxes = $this->taxService->computeTaxes([
            new Item(new Product('test1', 200, 'Farmitoo'), 7),
            new Item(new Product('test2', 400, 'Farmitoo'), 3),
            new Item(new Product('test3', 1500, 'Gallagher'), 4),
            new Item(new Product('test4', 600, 'Gallagher'), 9),
        ]);

        $this->assertEquals(20, $taxes['Farmitoo']['rate']);
        $this->assertEquals(520, $taxes['Farmitoo']['value']);
        $this->assertEquals(5, $taxes['Gallagher']['rate']);
        $this->assertEquals(570, $taxes['Gallagher']['value']);
    }

}