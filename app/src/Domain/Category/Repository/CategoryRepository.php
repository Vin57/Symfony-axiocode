<?php

namespace App\Domain\Category\Repository;

use App\Domain\Application\Repository\BaseRepository;
use App\Entity\Category;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

class CategoryRepository extends BaseRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Category::class);
    }

    /**
     * Build a {@see QueryBuilder} to query {@see Category}.
     * May be used in Form context to provide a list of
     * {@see Category} entities.
     * @return QueryBuilder
     */
    public function getAllCategoryQB(): QueryBuilder
    {
        return $this->resetAndGetQb()
            ->addOrderBy($this->alias . '.name');
    }
}
