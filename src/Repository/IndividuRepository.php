<?php

namespace App\Repository;

use App\Entity\Individu;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Individu>
 */
class IndividuRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Individu::class);
    }

    public function findOrphelins(): array
    {
        return $this->createQueryBuilder('i')
            ->where('i.pere IS NULL')
            ->andWhere('i.mere IS NULL')
            ->orderBy('i.nomNaissance', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function searchByName(string $query): array
    {
        return $this->createQueryBuilder('i')
            ->where('i.nomNaissance LIKE :query')
            ->orWhere('i.prenom1 LIKE :query')
            ->orWhere('i.prenom2 LIKE :query')
            ->orWhere('i.prenom3 LIKE :query')
            ->setParameter('query', '%' . $query . '%')
            ->orderBy('i.nomNaissance', 'ASC')
            ->setMaxResults(20)
            ->getQuery()
            ->getResult();
    }
}
