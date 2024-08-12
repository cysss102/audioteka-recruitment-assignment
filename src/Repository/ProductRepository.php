<?php

namespace App\Repository;

use App\Service\Catalog\Product;
use App\Service\Catalog\ProductProvider;
use App\Service\Catalog\ProductService;
use Doctrine\DBAL\LockMode;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Ramsey\Uuid\Uuid;

class ProductRepository implements ProductProvider, ProductService
{
    private EntityRepository $repository;

    public function __construct(private EntityManagerInterface $entityManager)
    {
        $this->repository = $this->entityManager->getRepository(\App\Entity\Product::class);
    }

    public function getProducts(int $page = 0, int $count = 3): iterable
    {
        return $this->repository->createQueryBuilder('p')
            ->orderBy('p.createdAt', 'DESC')
            ->setMaxResults($count)
            ->setFirstResult($page * $count)
            ->getQuery()
            ->getResult()
        ;
    }

    public function getTotalCount(): int
    {
        return $this->repository->createQueryBuilder('p')->select('count(p.id)')->getQuery()->getSingleScalarResult();
    }

    public function exists(string $productId): bool
    {
        return $this->repository->find($productId) !== null;
    }

    public function add(string $name, int $price): Product
    {
        $product = new \App\Entity\Product(Uuid::uuid4(), $name, $price);

        $this->entityManager->persist($product);
        $this->entityManager->flush();

        return $product;
    }

    public function remove(string $id): void
    {
        $product = $this->repository->find($id);
        if ($product !== null) {
            $this->entityManager->remove($product);
            $this->entityManager->flush();
        }
    }

    public function edit(string $productId, ?string $name, ?int $price): void
    {
        $this->entityManager->beginTransaction();
        try {
            $product = $this->entityManager->find(\App\Entity\Product::class, $productId, LockMode::PESSIMISTIC_WRITE);
            if (null !== $product) {
                $product->modifyProduct($name, $price);
                $this->entityManager->flush();
            }

            $this->entityManager->commit();
        } catch (\Exception $exception) {
            $this->entityManager->rollback();

            throw $exception;
        }
    }
}
