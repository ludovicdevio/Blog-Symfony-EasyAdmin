<?php

namespace App\Repository;


use App\Entity\Options;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Options|null find($id, $lockMode = null, $lockVersion = null)
 * @method Options|null findOneBy(array $criteria, array $orderBy = null)
 * @method Options[]    findAll()
 * @method Options[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OptionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Options::class);
    }

    public function findAllForTwig(): array
    {
        return $this->createQueryBuilder('o', 'o.name')
            ->getQuery()
            ->getResult();
    }

    public function getValue(string $name): mixed
    {
        try {
            return $this->createQueryBuilder('o')
                ->select('o.value')
                ->where('o.name = :name')
                ->setParameter('name', $name)
                ->getQuery()
                ->getSingleScalarResult();
        } catch (NoResultException|NonUniqueResultException) {
            return null;
        }
    }

    public function getIndexQueryBuilder(): QueryBuilder
    {
        return $this->createQueryBuilder('o')
            ->where("o.type IS NOT NULL")
            ->orderBy('o.label');
    }
}
