<?php

namespace App\Repository;

use App\Entity\WorkContract;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method WorkContract|null find($id, $lockMode = null, $lockVersion = null)
 * @method WorkContract|null findOneBy(array $criteria, array $orderBy = null)
 * @method WorkContract[]    findAll()
 * @method WorkContract[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class WorkContractRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, WorkContract::class);
    }

    // /**
    //  * @return WorkContract[] Returns an array of WorkContract objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('w')
            ->andWhere('w.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('w.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?WorkContract
    {
        return $this->createQueryBuilder('w')
            ->andWhere('w.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    public function findOneByStudent($studentId): ?WorkContract
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.student = :val')
            ->setParameter('val', $studentId)
            ->getQuery()
            ->getOneOrNullResult()
            ;
    }
}
