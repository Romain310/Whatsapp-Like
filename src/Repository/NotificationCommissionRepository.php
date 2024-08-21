<?php

namespace App\Repository;

use App\Entity\NotificationCommission;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<NotificationCommission>
 */
class NotificationCommissionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, NotificationCommission::class);
    }

        /**
         * @return NotificationCommission[] Returns an array of NotificationCommission objects
         */
        public function findByUser($user): array
        {
            return $this->createQueryBuilder('notification_commission')
                ->andWhere('notification_commission.user = :user')
                ->setParameter('user', $user)
                ->getQuery()
                ->getResult()
            ;
        }

        public function findByUserAndCommission($user, $commission): array
        {
            return $this->createQueryBuilder('notification_commission')
                ->andWhere('notification_commission.user = :user')
                ->andWhere('notification_commission.commission = :commission')
                ->setParameter('user', $user)
                ->setParameter('commission', $commission)
                ->getQuery()
                ->getResult()
            ;
        }

    //    public function findOneBySomeField($value): ?NotificationCommission
    //    {
    //        return $this->createQueryBuilder('n')
    //            ->andWhere('n.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
