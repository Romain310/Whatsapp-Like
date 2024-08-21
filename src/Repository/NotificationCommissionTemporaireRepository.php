<?php

namespace App\Repository;

use App\Entity\NotificationCommissionTemporaire;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<NotificationCommissionTemporaire>
 */
class NotificationCommissionTemporaireRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, NotificationCommissionTemporaire::class);
    }

        /**
         * @return NotificationCommissionTemporaire[] Returns an array of NotificationCommissionTemporaire objects
         */
        public function findByUser($user): array
        {
            return $this->createQueryBuilder('notification_commission_temporaire')
                ->andWhere('notification_commission_temporaire.user = :user')
                ->setParameter('user', $user)
                ->getQuery()
                ->getResult();
        }

    public function findByUserAndCommissionTemporaire($user, $commission): array
    {
        return $this->createQueryBuilder('notification_commission_temporaire')
            ->andWhere('notification_commission_temporaire.user = :user')
            ->andWhere('notification_commission_temporaire.commissionTemporaire = :commission')
            ->setParameter('user', $user)
            ->setParameter('commission', $commission)
            ->getQuery()
            ->getResult()
            ;
    }

    //    public function findOneBySomeField($value): ?NotificationCommissionTemporaire
    //    {
    //        return $this->createQueryBuilder('n')
    //            ->andWhere('n.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
