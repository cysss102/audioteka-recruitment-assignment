<?php

namespace App\Repository;

use App\Entity\Product;
use App\Exception\CartIsFullException;
use App\Service\Cart\Cart;
use App\Service\Cart\CartService;
use Doctrine\DBAL\LockMode;
use Doctrine\ORM\EntityManagerInterface;
use Ramsey\Uuid\Uuid;

class CartRepository implements CartService
{
    public function __construct(private EntityManagerInterface $entityManager) {}

    public function addProduct(string $cartId, string $productId): void
    {
        $this->entityManager->beginTransaction();
        try {
            $cart    = $this->entityManager->find(\App\Entity\Cart::class, $cartId, LockMode::PESSIMISTIC_WRITE);
            $product = $this->entityManager->find(Product::class, $productId);

            if (null === $cart || null === $product) {
                throw new \InvalidArgumentException(
                    sprintf(
                        'Cart (%s) or Product (%s) not found',
                        $cartId,
                        $productId
                    )
                );
            }

            if ($cart->isFull()) {
                throw new CartIsFullException();
            }

            $cart->addProduct($product);

            $this->entityManager->persist($cart);
            $this->entityManager->flush();

            $this->entityManager->commit();
        } catch (\Exception $exception) {
            $this->entityManager->rollback();

            throw $exception;
        }
    }

    public function removeProduct(string $cartId, string $productId): void
    {
        $cart    = $this->entityManager->find(\App\Entity\Cart::class, $cartId);
        $product = $this->entityManager->find(Product::class, $productId);

        if ($cart && $product && $cart->hasCartProduct($product)) {
            $cart->removeProduct($product);
            $this->entityManager->persist($cart);
            $this->entityManager->flush();
        }
    }

    public function create(): Cart
    {
        $cart = new \App\Entity\Cart(Uuid::uuid4()->toString());

        $this->entityManager->persist($cart);
        $this->entityManager->flush();

        return $cart;
    }
}