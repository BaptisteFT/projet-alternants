<?php

namespace App\Repository;

use App\Entity\Notification;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Notification|null find($id, $lockMode = null, $lockVersion = null)
 * @method Notification|null findOneBy(array $criteria, array $orderBy = null)
 * @method Notification[]    findAll()
 * @method Notification[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class NotificationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Notification::class);
    }

    // /**
    //  * @return Notification[] Returns an array of Notification objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('n')
            ->andWhere('n.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('n.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /**
     * @return Notification[] Returns an array of Notification objects
     */
    public function findNewNotification()
    {
        return $this->createQueryBuilder('n')
            ->andWhere('n.isArchived = :val')
            ->setParameter('val', false)
            ->orderBy('n.priority', 'ASC')
            ->getQuery()
            ->getResult()
            ;
    }

    /**
     * @return Notification[] Returns an array of Notification objects
     */
    public function findArchivedNotification()
    {
        return $this->createQueryBuilder('n')
            ->andWhere('n.isArchived = :val')
            ->setParameter('val', true)
            ->orderBy('n.priority', 'ASC')
            ->getQuery()
            ->getResult()
            ;
    }

    public function getNumberOfNewNotification()
    {
        $entityManager = $this->getEntityManager();

        $query = $entityManager->createQuery(
            'SELECT COUNT
            FROM App\Entity\Notification n
            WHERE n.isArchived = :value'
        )->setParameter('value', false);

        // returns an array of Product objects
        return $query->getResult();
    }

}
