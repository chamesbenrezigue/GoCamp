<?php

namespace App\Repository;

use App\Entity\InscriptionEvent;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method InscriptionEvent|null find($id, $lockMode = null, $lockVersion = null)
 * @method InscriptionEvent|null findOneBy(array $criteria, array $orderBy = null)
 * @method InscriptionEvent[]    findAll()
 * @method InscriptionEvent[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class InscriptionEventRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, InscriptionEvent::class);
    }

    // /**
    //  * @return InscriptionEvent[] Returns an array of InscriptionEvent objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('i.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?InscriptionEvent
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
