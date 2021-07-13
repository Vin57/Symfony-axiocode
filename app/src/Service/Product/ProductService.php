<?php


namespace App\Service\Product;


use App\Entity\Product;
use App\Form\SearchProductData;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\ORMException;
use Doctrine\ORM\Query;

class ProductService implements ProductServiceInterface
{
    private EntityManagerInterface $entityManager;
    private ProductRepository $productRepository;

    public function __construct(
        EntityManagerInterface $entityManager,
        ProductRepository $productRepository
    )
    {
        $this->entityManager = $entityManager;
        $this->productRepository = $productRepository;
    }

    public function search (SearchProductData $searchProductData): Query
    {
        return $this->productRepository->search($searchProductData);
    }

    public function new (Product $product): ?Product {
        $this->entityManager->persist($product);
        $this->entityManager->flush();
        return $product;
    }

    public function update (Product $product): bool {
        $this->entityManager->persist($product);
        $this->entityManager->flush();
        return true;
    }
}