<?php

namespace App\Tests\Functional\Controller\Cart\RemoveProductController;

use App\Entity\Cart;
use App\Entity\Product;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Persistence\ObjectManager;

class RemoveProductControllerFixture extends AbstractFixture
{

    public function load(ObjectManager $manager): void
    {
        $product = new Product('d11e1e69-cca7-40a1-8273-9d93c8346efd', 'Product 1', 1990);
        $manager->persist($product);

        $product2 = new Product('7bcf6fe9-e831-4776-a9df-76a702233adc', 'Product 2', 2990);
        $manager->persist($product2);

        $cart = new Cart('97e385fe-9876-45fc-baa0-4f2f0df90950');
        $cart->addProduct($product);
        $manager->persist($cart);

        $product3 = new Product('81cccda0-2c7b-4108-8e50-8867e5312e05', 'Product 3', 3990);
        $manager->persist($product3);

        $cart2 = new Cart('4a41e626-21ca-47f7-b70a-9a189f9907f5');
        $cart2->addProduct($product3);
        $cart2->addProduct($product3);
        $manager->persist($cart2);

        $manager->flush();
    }
}