<?php

namespace App\Tests\Functional\Controller\Catalog\EditController;

use App\Tests\Functional\WebTestCase;

class EditControllerTest extends WebTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->loadFixtures(new EditControllerFixture());
    }

    public function test_modify_product_name_and_price(): void
    {
        $this->client->request('PATCH', '/products/fbcb8c51-5dcc-4fd4-a4cd-ceb9b400bff7', [
            'name' => 'Product name new',
            'price' => 2000,
        ]);
        self::assertResponseStatusCodeSame(202);

        $this->client->request('GET', '/products');
        self::assertResponseStatusCodeSame(200);

        $response = $this->getJsonResponse();
        self::assertCount(1, $response['products']);
        self::assertequals('Product name new', $response['products'][0]['name']);
        self::assertequals(2000, $response['products'][0]['price']);
    }

    public function test_modify_product_name(): void
    {
        $this->client->request('PATCH', '/products/fbcb8c51-5dcc-4fd4-a4cd-ceb9b400bff7', [
            'name' => 'Product name new',
        ]);
        self::assertResponseStatusCodeSame(202);

        $this->client->request('GET', '/products');
        self::assertResponseStatusCodeSame(200);

        $response = $this->getJsonResponse();
        self::assertCount(1, $response['products']);
        self::assertequals('Product name new', $response['products'][0]['name']);
        self::assertequals(1990, $response['products'][0]['price']);
    }

    public function test_modify_product_price(): void
    {
        $this->client->request('PATCH', '/products/fbcb8c51-5dcc-4fd4-a4cd-ceb9b400bff7', [
            'price' => 5555,
        ]);
        self::assertResponseStatusCodeSame(202);

        $this->client->request('GET', '/products');
        self::assertResponseStatusCodeSame(200);

        $response = $this->getJsonResponse();
        self::assertCount(1, $response['products']);
        self::assertequals('Product 1', $response['products'][0]['name']);
        self::assertequals(5555, $response['products'][0]['price']);
    }

    public function test_invalid_product_name_and_price(): void
    {
        $this->client->request('PATCH', '/products/fbcb8c51-5dcc-4fd4-a4cd-ceb9b400bff7', [
            'name' => '',
            'price' => '',
        ]);
        self::assertResponseStatusCodeSame(400);

        $response = $this->getJsonResponse();
        self::assertEquals(['error_message' => 'No changes provided.'], $response);

        $this->client->request('PATCH', '/products/fbcb8c51-5dcc-4fd4-a4cd-ceb9b400bff7');
        self::assertResponseStatusCodeSame(400);

        $response = $this->getJsonResponse();
        self::assertEquals(['error_message' => 'No changes provided.'], $response);
    }

    public function test_invalid_product_price(): void
    {
        $this->client->request('PATCH', '/products/fbcb8c51-5dcc-4fd4-a4cd-ceb9b400bff7', [
            'price' => -10,
        ]);
        self::assertResponseStatusCodeSame(422);

        $response = $this->getJsonResponse();
        self::assertEquals(['error_message' => 'Invalid price.'], $response);
    }
}