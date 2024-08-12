<?php

declare(strict_types=1);

namespace App\Messenger;

use App\Service\Catalog\ProductService;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class EditProductFromCatalogHandler implements MessageHandlerInterface
{
    public function __construct(private readonly ProductService $service) {}

    public function __invoke(EditProductFromCatalog $command): void
    {
        $this->service->edit($command->productId, $command->name, $command->price);
    }
}
