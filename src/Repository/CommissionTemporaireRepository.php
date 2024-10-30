<?php

namespace App\Repository;

use App\Entity\CommissionTemporaire;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<CommissionTemporaire>
 */
class CommissionTemporaireRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CommissionTemporaire::class);
    }

    /**
     * @return CommissionTemporaire[] Returns an array of CommissionTemporaire objects
     */
    public function findNonClos($value): array
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.debut <= :val')
            ->andWhere('c.cloture >= :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->getQuery()
            ->getResult()
            ;
    }

    public function findClos($value): array
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.cloture < :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->getQuery()
            ->getResult()
            ;
    }

    public function findFutur($value): array
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.debut > :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->getQuery()
            ->getResult()
            ;
    }

    public function findByUserAndActive($user, $active): array
    {
        return $this->createQueryBuilder('ct')
            ->join('ct.notificationsUsers', 'nct', 'WITH', 'nct.commissionTemporaire = ct.id')
            ->andWhere('nct.active = :val')
            ->andWhere('nct.user = :user')
            ->setParameter('val', $active)
            ->setParameter('user', $user)
            ->getQuery()
            ->getResult()
            ;
    }

    //    public function findOneBySomeField($value): ?CommissionTemporaire
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
