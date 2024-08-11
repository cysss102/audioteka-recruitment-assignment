<?php

declare(strict_types=1);

namespace App\Service\Cart;

interface CartProduct
{
    public function increaseQuantity(): void;

    public function decreaseQuantity(): void;
}