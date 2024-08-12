<?php

declare(strict_types=1);

namespace Tests\Unit\Entity;

use App\Entity\Product;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

class ProductTest extends TestCase
{
    private string $validUuid;
    private string $validName;
    private int $validPrice;
    private \DateTimeImmutable $validDate;

    protected function setUp(): void
    {
        $this->validUuid = Uuid::uuid4()->toString();
        $this->validName = "Test Product";
        $this->validPrice = 1000;
        $this->validDate = new \DateTimeImmutable();
    }

    public function testConstructorWithAllParameters()
    {
        $product = new Product($this->validUuid, $this->validName, $this->validPrice, $this->validDate);

        $this->assertEquals($this->validUuid, $product->getId());
        $this->assertEquals($this->validName, $product->getName());
        $this->assertEquals($this->validPrice, $product->getPrice());
        $this->assertSame($this->validDate, $product->getCreatedAt());
    }

    public function testConstructorWithoutCreatedAt()
    {
        $product = new Product($this->validUuid, $this->validName, $this->validPrice);

        $this->assertEquals($this->validUuid, $product->getId());
        $this->assertEquals($this->validName, $product->getName());
        $this->assertEquals($this->validPrice, $product->getPrice());
        $this->assertInstanceOf(\DateTimeImmutable::class, $product->getCreatedAt());
    }

    public function testGetId()
    {
        $product = new Product($this->validUuid, $this->validName, $this->validPrice);
        $this->assertEquals($this->validUuid, $product->getId());
    }

    public function testGetName()
    {
        $product = new Product($this->validUuid, $this->validName, $this->validPrice);
        $this->assertEquals($this->validName, $product->getName());
    }

    public function testGetPrice()
    {
        $product = new Product($this->validUuid, $this->validName, $this->validPrice);
        $this->assertEquals($this->validPrice, $product->getPrice());
    }

    public function testGetCreatedAt()
    {
        $product = new Product($this->validUuid, $this->validName, $this->validPrice, $this->validDate);
        $this->assertSame($this->validDate, $product->getCreatedAt());
    }

    public function testModifyProductBothFields()
    {
        $product = new Product($this->validUuid, $this->validName, $this->validPrice);
        $newName = "Modified Product";
        $newPrice = 2000;

        $product->modifyProduct($newName, $newPrice);

        $this->assertEquals($newName, $product->getName());
        $this->assertEquals($newPrice, $product->getPrice());
    }

    public function testModifyProductNameOnly()
    {
        $product = new Product($this->validUuid, $this->validName, $this->validPrice);
        $newName = "Modified Product";

        $product->modifyProduct($newName, null);

        $this->assertEquals($newName, $product->getName());
        $this->assertEquals($this->validPrice, $product->getPrice());
    }

    public function testModifyProductPriceOnly()
    {
        $product = new Product($this->validUuid, $this->validName, $this->validPrice);
        $newPrice = 2000;

        $product->modifyProduct(null, $newPrice);

        $this->assertEquals($this->validName, $product->getName());
        $this->assertEquals($newPrice, $product->getPrice());
    }

    public function testModifyProductNoChange()
    {
        $product = new Product($this->validUuid, $this->validName, $this->validPrice);

        $product->modifyProduct(null, null);

        $this->assertEquals($this->validName, $product->getName());
        $this->assertEquals($this->validPrice, $product->getPrice());
    }
}