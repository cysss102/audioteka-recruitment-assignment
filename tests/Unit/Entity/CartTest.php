<?php

namespace Tests\Unit\Entity;

use App\Entity\Cart;
use App\Entity\Product;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

class CartTest extends TestCase
{
    private Cart $cart;

    protected function setUp(): void
    {
        $this->cart = new Cart(Uuid::uuid4()->toString());
    }

    public function testGetId(): void
    {
        // Given
        $uuid = Uuid::uuid4()->toString();
        $cart = new Cart($uuid);

        // When
        $result = $cart->getId();

        // Then
        $this->assertEquals($uuid, $result);
        $this->assertTrue(Uuid::isValid($result));
    }

    public function testGetTotalPrice(): void
    {
        // Given
        $product1 = $this->createProductMock(100, '1');
        $product2 = $this->createProductMock(200, '2');
        $this->cart->addProduct($product1);
        $this->cart->addProduct($product2);
        $this->cart->addProduct($product1);

        // When
        $totalPrice = $this->cart->getTotalPrice();

        // Then
        $this->assertEquals(400, $totalPrice);
    }

    public function testIsFull(): void
    {
        // Given
        $product = $this->createProductMock(100, '1');

        // When & Then
        $this->assertFalse($this->cart->isFull());

        // When
        $this->cart->addProduct($product);
        $this->cart->addProduct($product);

        // Then
        $this->assertFalse($this->cart->isFull());

        // When
        $this->cart->addProduct($product);

        // Then
        $this->assertTrue($this->cart->isFull());
    }

    public function testGetCartProduct(): void
    {
        // Given
        $product = $this->createProductMock(100, '1');
        $this->cart->addProduct($product);

        // When
        $cartProducts = $this->cart->getCartProduct();

        // Then
        $this->assertInstanceOf(\Iterator::class, $cartProducts);
        $this->assertCount(1, iterator_to_array($cartProducts));
    }

    public function testHasCartProduct(): void
    {
        // Given
        $product1 = $this->createProductMock(100, '1');
        $product2 = $this->createProductMock(200, '2');
        $this->cart->addProduct($product1);

        // When & Then
        $this->assertTrue($this->cart->hasCartProduct($product1));
        $this->assertFalse($this->cart->hasCartProduct($product2));
    }

    public function testAddProduct(): void
    {
        // Given
        $product = $this->createProductMock(100, '1');

        // When
        $this->cart->addProduct($product);
        $this->cart->addProduct($product);

        // Then
        $cartProducts = iterator_to_array($this->cart->getCartProduct());
        $this->assertCount(1, $cartProducts);
        $this->assertEquals(2, $cartProducts[0]->getQuantity());
    }

    public function testRemoveProduct(): void
    {
        // Given
        $product = $this->createProductMock(100, '1');
        $this->cart->addProduct($product);
        $this->cart->addProduct($product);

        // When
        $this->cart->removeProduct($product);

        // Then
        $cartProducts = iterator_to_array($this->cart->getCartProduct());
        $this->assertCount(1, $cartProducts);
        $this->assertEquals(1, $cartProducts[0]->getQuantity());

        // When
        $this->cart->removeProduct($product);

        // Then
        $this->assertCount(0, iterator_to_array($this->cart->getCartProduct()));
    }

    private function createProductMock(int $price, string $id): Product
    {
        $product = $this->createMock(Product::class);
        $product->method('getPrice')->willReturn($price);
        $product->method('getId')->willReturn($id);

        return $product;
    }
}