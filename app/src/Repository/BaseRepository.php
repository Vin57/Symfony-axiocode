<?php


namespace App\Repository;


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

    /**
     * @return QueryBuilder|null
     */
    protected function getQb(): ?QueryBuilder
    {
        return $this->qb;
    }

    protected function setQb(?QueryBuilder $qb): void
    {
        $this->qb = $qb;
    }

    public function getAlias(): ?string
    {
        return $this->alias;
    }

    public function setAlias(?string $alias): void
    {
        $this->alias = $alias;
    }

    public function getIndexedBy(): ?string
    {
        return $this->indexedBy;
    }

    public function setIndexedBy(?string $indexedBy): void
    {
        $this->indexedBy = $indexedBy;
    }

    protected function resetAndGetQb() : QueryBuilder {
        $this->setQb($this->createQueryBuilder($this->alias, $this->indexedBy));
        return $this->getQb();
    }

}