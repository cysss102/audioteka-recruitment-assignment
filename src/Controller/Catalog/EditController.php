<?php

namespace App\Controller\Catalog;

use App\Entity\Product;
use App\Messenger\EditProductFromCatalog;
use App\Messenger\MessageBusAwareInterface;
use App\Messenger\MessageBusTrait;
use App\ResponseBuilder\ErrorBuilder;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route("/products/{product}", name: "product-edit", methods: ["PATCH"])]
class EditController extends AbstractController implements MessageBusAwareInterface
{
    use MessageBusTrait;

    public function __construct(private ErrorBuilder $errorBuilder) {}

    public function __invoke(Product $product, Request $request): Response
    {
        $name = trim($request->get('name') ?? '') ?: null;
        $price = is_numeric($request->get('price')) ? (int)$request->get('price') : null;

        if (null === $name && null === $price) {
            return new JsonResponse(
                $this->errorBuilder->__invoke('No changes provided.'),
                Response::HTTP_BAD_REQUEST
            );
        }

        if (is_numeric($price) && $price < 1) {
            return new JsonResponse(
                $this->errorBuilder->__invoke('Invalid price.'),
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }

        $this->dispatch(new EditProductFromCatalog(
                productId: $product->getId(),
                name: $name,
                price: $price,
            )
        );

        return new Response('', Response::HTTP_ACCEPTED);
    }
}