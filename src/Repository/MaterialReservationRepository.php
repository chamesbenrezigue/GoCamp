<?php

namespace App\Repository;

use App\Entity\MaterialReservation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use http\Client\Curl\User;

/**
 * @method MaterialReservation|null find($id, $lockMode = null, $lockVersion = null)
 * @method MaterialReservation|null findOneBy(array $criteria, array $orderBy = null)
 * @method MaterialReservation[]    findAll()
 * @method MaterialReservation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MaterialReservationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MaterialReservation::class);
    }

    // /**
    //  * @return MaterialReservation[] Returns an array of MaterialReservation objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('m.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?MaterialReservation
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
    public function findByDateRange($minDate,$maxDate,$user)
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.dateStart >= :minDate')
            ->setParameter('minDate',$minDate)
            ->andWhere('m.user = :user')
            ->setParameter('user',$user)
            ->andWhere('m.dateEnd <= :maxDate')
            ->setParameter('maxDate',$maxDate)
            ->orderBy('m.id','ASC')
            ->getQuery()
            ->getResult()
            ;

    }


}
