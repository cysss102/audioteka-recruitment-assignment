<?php

namespace App\Tests\Functional\Controller\Catalog\EditController;

use App\Entity\Cart;
use App\Entity\Product;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Persistence\ObjectManager;

class EditControllerFixture extends AbstractFixture
{
    public function load(ObjectManager $manager): void
    {
        $product = new Product('fbcb8c51-5dcc-4fd4-a4cd-ceb9b400bff7', 'Product 1', 1990);

        $manager->persist($product);
        $manager->flush();
    }
}