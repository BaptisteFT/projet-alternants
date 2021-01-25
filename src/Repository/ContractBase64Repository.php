<?php

namespace App\Repository;

use App\Entity\Contract;
use App\Entity\ContractBase64;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ContractBase64|null find($id, $lockMode = null, $lockVersion = null)
 * @method ContractBase64|null findOneBy(array $criteria, array $orderBy = null)
 * @method ContractBase64[]    findAll()
 * @method ContractBase64[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ContractBase64Repository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ContractBase64::class);
    }

    // /**
    //  * @return ContractBase64[] Returns an array of ContractBase64 objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ContractBase64
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
    public function findOneByContract($contractId) : ?ContractBase64
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.contract = :val')
            ->setParameter('val', $contractId)
            ->getQuery()
            ->getOneOrNullResult()
            ;
    }
}
