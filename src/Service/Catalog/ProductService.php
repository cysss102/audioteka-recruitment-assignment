<?php

namespace App\Service\Catalog;

interface ProductService
{
    public function add(string $name, int $price): Product;

    public function remove(string $id): void;

    public function edit(string $productId, ?string $name, ?int $price): void;
}