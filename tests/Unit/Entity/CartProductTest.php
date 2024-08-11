<?php

namespace Tests\Unit\Entity;

use App\Entity\Cart;
use App\Entity\CartProduct;
use App\Entity\Product;
use PHPUnit\Framework\TestCase;

class CartProductTest extends TestCase
{
    private Cart $cart;
    private Product $product;
    private CartProduct $cartProduct;

    protected function setUp(): void
    {
        $this->cart = $this->createMock(Cart::class);
        $this->product = $this->createMock(Product::class);
        $this->cartProduct = new CartProduct($this->cart, $this->product);
    }

    public function testGetCart(): void
    {
        // Given
        // CartProduct is created in setUp

        // When
        $result = $this->cartProduct->getCart();

        // Then
        $this->assertSame($this->cart, $result);
    }

    public function testGetProduct(): void
    {
        // Given
        // CartProduct is created in setUp

        // When
        $result = $this->cartProduct->getProduct();

        // Then
        $this->assertSame($this->product, $result);
    }

    public function testGetQuantity(): void
    {
        // Given
        // CartProduct is created in setUp with default quantity

        // When
        $result = $this->cartProduct->getQuantity();

        // Then
        $this->assertEquals(1, $result);
    }

    public function testIncreaseQuantity(): void
    {
        // Given
        // CartProduct is created in setUp with quantity 1

        // When
        $this->cartProduct->increaseQuantity();

        // Then
        $this->assertEquals(2, $this->cartProduct->getQuantity());

        // When
        $this->cartProduct->increaseQuantity();

        // Then
        $this->assertEquals(3, $this->cartProduct->getQuantity());
    }

    public function testDecreaseQuantity(): void
    {
        // Given
        $this->cartProduct->increaseQuantity();
        $this->cartProduct->increaseQuantity();
        $this->assertEquals(3, $this->cartProduct->getQuantity());

        // When
        $this->cartProduct->decreaseQuantity();

        // Then
        $this->assertEquals(2, $this->cartProduct->getQuantity());

        // When
        $this->cartProduct->decreaseQuantity();

        // Then
        $this->assertEquals(1, $this->cartProduct->getQuantity());

        // When
        $this->cartProduct->decreaseQuantity();

        // Then
        $this->assertEquals(1, $this->cartProduct->getQuantity(), "Quantity should not decrease below 1");
    }
}