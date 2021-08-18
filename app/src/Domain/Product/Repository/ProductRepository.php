<?php

namespace App\Domain\Product\Repository;

use App\Domain\Application\Repository\BaseRepository;
use App\Domain\Product\Form\SearchProductData;
use App\Entity\Product;
use Doctrine\ORM\Query;
use Doctrine\Persistence\ManagerRegistry;

class ProductRepository extends BaseRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class, 'p');
    }

    public function search(SearchProductData $searchProductData): Query
    {
        $qb = $this->resetAndGetQb()
            ->addSelect('opinion', 'picture')
            ->leftJoin($this->alias . '.opinions', 'opinion')
            ->leftJoin($this->alias . '.pictures', 'picture');

        if ($name = $searchProductData->getName()) {
            $qb->andWhere($this->alias. '.name LIKE :name')
                ->setParameter(':name', '%' . $name . '%');
        }

        if ($category = $searchProductData->getCategory()) {
            $categories = [$category];
            if ($category->getAllChildCategories()) {
                $categories = array_merge($categories, $category->getAllChildCategories());
            }
            $qb->innerJoin(
                $this->alias . '.category',
                'category',
                Query\Expr\Join::WITH,
                'category.id IN (:categories)')
                ->setParameter(':categories', $categories);
        } else {
            $qb->leftJoin($this->alias . '.category', 'category');
        }
        return $qb->getQuery();
    }
}
