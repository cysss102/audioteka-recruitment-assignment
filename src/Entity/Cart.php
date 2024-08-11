<?php

namespace App\Entity;

use App\Service\Catalog\Product;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JetBrains\PhpStorm\Pure;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

#[ORM\Entity]
class Cart implements \App\Service\Cart\Cart
{
    public const CAPACITY = 3;

    #[ORM\Id]
    #[ORM\Column(type: 'uuid', nullable: false)]
    private UuidInterface $id;

    #[ORM\OneToMany(mappedBy: 'cart', targetEntity: CartProduct::class, cascade: [
        'persist',
        'remove'
    ], orphanRemoval: true)]
    private Collection $cartProducts;

    public function __construct(string $id)
    {
        $this->id           = Uuid::fromString($id);
        $this->cartProducts = new ArrayCollection();
    }

    public function getId(): string
    {
        return $this->id->toString();
    }

    public function getTotalPrice(): int
    {
        return array_sum($this->cartProducts->map(function (CartProduct $cartProduct) {
            return $cartProduct->getProduct()->getPrice() * $cartProduct->getQuantity();
        })->toArray());
    }

    #[Pure]
    public function isFull(): bool
    {
        $total = 0;
        foreach ($this->cartProducts as $cartProduct) {
            $total += $cartProduct->getQuantity();
        }

        return $total >= self::CAPACITY;
    }


    public function getCartProduct(): iterable
    {
        return $this->cartProducts->getIterator();
    }

    #[Pure]
    public function hasCartProduct(\App\Entity\Product $product): bool
    {
        return null !== $this->fetchCartProduct($product);
    }

    public function addProduct(\App\Entity\Product $product): void
    {
        $cartProduct = $this->fetchCartProduct($product);
        if ($cartProduct) {
            $cartProduct->increaseQuantity();

            return;
        }

        $this->cartProducts->add(new CartProduct($this, $product));
    }

    public function removeProduct(\App\Entity\Product $product): void
    {
        $cartProduct = $this->fetchCartProduct($product);
        if ($cartProduct->getQuantity() > 1) {
            $cartProduct->decreaseQuantity();
            return;
        }

        $this->cartProducts->removeElement($cartProduct);
    }

    private function fetchCartProduct(Product $product): ?CartProduct
    {
        foreach ($this->cartProducts as $cartProduct) {
            if ($cartProduct->getProduct()->getId() === $product->getId()) {
                return $cartProduct;
            }
        }

        return null;
    }
}
