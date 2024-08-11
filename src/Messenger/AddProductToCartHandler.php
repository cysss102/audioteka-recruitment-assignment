<?php

namespace App\Messenger;

use App\Exception\CartIsFullException;
use App\Service\Cart\CartService;
use Symfony\Component\Messenger\Exception\UnrecoverableMessageHandlingException;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class AddProductToCartHandler implements MessageHandlerInterface
{
    public function __construct(private CartService $service) { }

    public function __invoke(AddProductToCart $command): void
    {
        try {
            $this->service->addProduct($command->cartId, $command->productId);
        } catch (CartIsFullException|\InvalidArgumentException $exception) {
            // todo: logger

            throw new UnrecoverableMessageHandlingException(
                $exception->getMessage(),
                0,
                $exception
            );
        }
    }
}