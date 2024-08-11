<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: "cart_products")]
class CartProduct implements \App\Service\Cart\CartProduct
{
    #[ORM\Id]
    #[ORM\ManyToOne(targetEntity: Cart::class, inversedBy: 'cartProducts')]
    #[ORM\JoinColumn(name: "cart_id", referencedColumnName: "id", nullable: false)]
    private Cart $cart;

    #[ORM\Id]
    #[ORM\ManyToOne(targetEntity: Product::class)]
    #[ORM\JoinColumn(name: "product_id", referencedColumnName: "id", nullable: false)]
    private Product $product;

    #[ORM\Column(type: 'integer', nullable: false, options: ['default' => 1])]
    private int $quantity = 1;

    public function __construct(Cart $cart, Product $product)
    {
        $this->cart    = $cart;
        $this->product = $product;
    }

    public function getCart(): Cart
    {
        return $this->cart;
    }

    public function getProduct(): Product
    {
        return $this->product;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }

    public function increaseQuantity(): void
    {
        $this->quantity++;
    }

    public function decreaseQuantity(): void
    {
        if ($this->quantity > 1) {
            $this->quantity--;
        }
    }
}

