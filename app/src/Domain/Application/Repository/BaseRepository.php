<?php


namespace App\Domain\Application\Repository;


use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

abstract class BaseRepository extends ServiceEntityRepository
{
    private ?QueryBuilder $qb;
    protected ?string $alias;
    protected ?string $indexedBy;

    public function __construct(ManagerRegistry $registry, string $entityClassName, string $alias = null, ?string $identifier = null)
    {
        parent::__construct($registry, $entityClassName);
        $this->alias = $alias ?? str_replace('\\', '_', $this->getClassName());
        $this->indexedBy = $identifier;
        $this->resetAndGetQb();
    }

    protected function getQb(): ?QueryBuilder
    {
        return $this->qb;
    }

    protected function setQb(?QueryBuilder $qb): void
    {
        $this->qb = $qb;
    }

    /**
     * Reset and get query builder for this entity repository.
     * @return QueryBuilder
     */
    protected function resetAndGetQb() : QueryBuilder {
        $this->setQb($this->createQueryBuilder($this->alias, $this->indexedBy));
        return $this->getQb();
    }

}