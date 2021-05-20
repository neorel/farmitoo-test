<?php

namespace App\Service;

use App\Entity\Order;
use App\Entity\Item;
use App\Entity\Promotion;

class OrderService {

    public function __construct(
        protected PromotionService $promotionService,
        protected DeliveryService $deliveryService,
        protected TaxService $taxService,
    ){}

    public function computeOrder(Order $order): array 
    {
        $computedCart = [
            'order' => $order,
            'total_items' => $this->computeSubTotal($order->getItems())
        ];
        $computedCart['promotions'] = $this->computePromotions($computedCart['total_items']);

        $freeDelivery = array_reduce(
            $computedCart['promotions'],
            fn($carry, array $promotion) => $carry || $promotion['entity']->isFreeDelivery(),
            false
        );

        $computedCart['deliveries'] = $freeDelivery ? [] : $this->computeDelivery($order->getItems());

        $computedCart['total_tax_excl'] = $this->computeTotalTaxExcl(
            $computedCart['total_items'],
            $computedCart['promotions'],
            $computedCart['deliveries']
        );

        $computedCart['taxes'] = $this->computeTaxes($order->getItems(), $computedCart['promotions']);

        $computedCart['total_tax_incl'] = $this->computeTotalTaxIncl($computedCart['total_tax_excl'], $computedCart['taxes']);

        return $computedCart;
    }

    protected function computeSubTotal(array $items): int
    {
        return array_reduce($items, function(int $carry, Item $item) {
            return $carry + $item->getQuantity() * $item->getProduct()->getPrice();
        }, 0);
    }

    protected function computePromotions(int $totalAmount): array
    {
        return array_map(
            fn(Promotion $promotion) => [
                'entity' => $promotion,
                'value' => $totalAmount * ($promotion->getReduction() / 100)
            ],
            array_filter(
                $this->promotionService->getPromotions(),
                fn(Promotion $promotion) => $promotion->getMinAmount() <= $totalAmount
            )
        );
    }

    protected function computeDelivery(array $items): array {
        return $this->deliveryService->computeDeliveries($items);
    }

    protected function computeTotalTaxExcl(int $totalItems, array $promotions, array $deliveries): int
    {
        return $totalItems
            - array_sum(
                array_map(
                    fn(array $promotion) => $promotion['value'],
                    $promotions
                )
            )
            + array_sum($deliveries);
    }

    protected function computeTaxes(array $items, array $promotions): array
    {
        $promotionRate = 1 - (
            array_sum(
                array_map(
                    fn(array $promotion) => $promotion['entity']->getReduction(),
                    $promotions
                )
            )
            / 100
        );
        return array_map(
            function($tax) use($promotionRate) {
                $tax['value'] *= $promotionRate;
                return $tax;
            },
            $this->taxService->computeTaxes($items)
        );
    }

    protected function computeTotalTaxIncl(int $totalTaxExcl, $taxes) {
        return $totalTaxExcl + array_sum(array_map(fn($tax) => $tax['value'], $taxes));
    }
}