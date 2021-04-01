<?php

namespace App\Repository;

use App\Entity\ReservationMateriel;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ReservationMateriel|null find($id, $lockMode = null, $lockVersion = null)
 * @method ReservationMateriel|null findOneBy(array $criteria, array $orderBy = null)
 * @method ReservationMateriel[]    findAll()
 * @method ReservationMateriel[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReservationMaterielRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ReservationMateriel::class);
    }

    // /**
    //  * @return ReservationMateriel[] Returns an array of ReservationMateriel objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('r.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ReservationMateriel
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
