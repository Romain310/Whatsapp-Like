<?php

namespace App\Repository;

use App\Entity\Commission;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Commission>
 */
class CommissionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Commission::class);
    }

    //    /**
    //     * @return Commission[] Returns an array of Commission objects
    //     */
        public function findByUserAndActive($user, $active): array
        {
            return $this->createQueryBuilder('c')
            ->join('c.notificationsUsers', 'nu', 'WITH', 'nu.commission = c.id')
                ->andWhere('nu.active = :val')
                ->andWhere('nu.user = :user')
                ->setParameter('val', $active)
                ->setParameter('user', $user)
                ->getQuery()
                ->getResult()
            ;
        }

    //    public function findOneBySomeField($value): ?Commission
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
