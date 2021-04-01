<?php

namespace App\Repository;

use App\Entity\SubjectResponse;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method SubjectResponse|null find($id, $lockMode = null, $lockVersion = null)
 * @method SubjectResponse|null findOneBy(array $criteria, array $orderBy = null)
 * @method SubjectResponse[]    findAll()
 * @method SubjectResponse[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SubjectResponseRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SubjectResponse::class);
    }

    // /**
    //  * @return SubjectResponse[] Returns an array of SubjectResponse objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?SubjectResponse
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
