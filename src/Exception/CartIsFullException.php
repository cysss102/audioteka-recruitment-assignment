<?php

declare(strict_types=1);

namespace App\Exception;

class CartIsFullException extends \Exception
{
    public function __construct()
    {
        parent::__construct('Cart is full');
    }
}