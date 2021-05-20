<?php


namespace App\Entity;


class Item
{
    public function __construct(
        protected Product $product, 
        protected int $quantity = 1
    ) {}

    public function getProduct(): Product
    {
        return $this->product;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): self
    {
        $this->quantity = $quantity;
        return $this;
    }
}
