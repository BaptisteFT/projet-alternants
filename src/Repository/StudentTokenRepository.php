<?php

namespace App\Repository;

use App\Entity\StudentToken;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method StudentToken|null find($id, $lockMode = null, $lockVersion = null)
 * @method StudentToken|null findOneBy(array $criteria, array $orderBy = null)
 * @method StudentToken[]    findAll()
 * @method StudentToken[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class StudentTokenRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, StudentToken::class);
    }

    // /**
    //  * @return StudentToken[] Returns an array of StudentToken objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?StudentToken
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
