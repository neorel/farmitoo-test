<?php

namespace App\Service;

use App\Entity\Promotion;

class PromotionService {

    /**
     * @var array
     */
    protected $promotions = [];

    public function __construct()
    {
        $this->promotions = [
            new Promotion(50000, 8, false)
        ];
    }

    public function getPromotions(): array
    {
        return $this->promotions;
    }

}