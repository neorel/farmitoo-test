<?php


namespace App\Entity;

class Order
{
    /**
     * @var array
     */
    protected $items = [];

    public function getItems(): array
    {
        return $this->items;
    }

    public function addProduct(Product $product, int $quantity = 1): self
    {
        /** @var Item $item */
        foreach($this->items as $item) {
            if($item->getProduct() === $product) {
                $item->setQuantity($item->getQuantity() + $quantity);
                return $this;
            }
        }

        $this->items[] = new Item($product, $quantity);

        return $this;
    }
}
