<?php

namespace App\Repository;

use App\Entity\JobInfo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method JobInfo|null find($id, $lockMode = null, $lockVersion = null)
 * @method JobInfo|null findOneBy(array $criteria, array $orderBy = null)
 * @method JobInfo[]    findAll()
 * @method JobInfo[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class JobInfoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, JobInfo::class);
    }
    public function findByStudent($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.student = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(100)
            ->getQuery()
            ->getResult()
            ;
    }

    public function findById($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.id = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(1)
            ->getQuery()
            ->getResult()
            ;
    }
    // /**
    //  * @return JobInfo[] Returns an array of JobInfo objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('j')
            ->andWhere('j.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('j.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?JobInfo
    {
        return $this->createQueryBuilder('j')
            ->andWhere('j.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
