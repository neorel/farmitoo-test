<?php

namespace App\Controller;

use App\Entity\Order;
use App\Entity\Product;
use App\Service\OrderService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class MainController extends AbstractController
{
    public function index(OrderService $orderService): Response
    {
        $product1 = new Product('Cuve à gasoil', 250000, 'Farmitoo');
        $product2 = new Product('Nettoyant pour cuve', 5000, 'Farmitoo');
        $product3 = new Product('Piquet de clôture', 1000, 'Gallagher');

        // Je passe une commande avec
        // Cuve à gasoil x1
        // Nettoyant pour cuve x3
        // Piquet de clôture x5

        $order = new Order();
        $order->addProduct($product1, 1);
        $order->addProduct($product2, 3);
        $order->addProduct($product3, 5);

        return $this->render(
            'cart.html.twig', 
            $orderService->computeOrder($order)
        );
    }
}
