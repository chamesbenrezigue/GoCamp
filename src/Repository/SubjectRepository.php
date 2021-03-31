<?php

namespace App\Repository;

use App\Entity\Subject;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Subject|null find($id, $lockMode = null, $lockVersion = null)
 * @method Subject|null findOneBy(array $criteria, array $orderBy = null)
 * @method Subject[]    findAll()
 * @method Subject[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SubjectRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Subject::class);
    }
    public function paginatedSubjects($page, $limit)
    {
        $query = $this -> createQueryBuilder('a')
            -> orderBy('a.createdAt', 'ASC')
            -> setFirstResult(($page * $limit) - $limit)
            -> setMaxResults($limit);
        return $query -> getQuery() -> getResult();
    }

    public function getTotalSubjects(){
        $query = $this->createQueryBuilder('a')
            ->select('COUNT(a)');

        // On filtre les données
        // if($filters != null){
        //   $query->andWhere('a.forum IN(:cats)')
        //     ->setParameter(':cats', array_values($filters));
        //}

        return $query->getQuery()->getSingleScalarResult();
    }
    function SearchSubject($category){
        return $this->createQueryBuilder('s')
            ->where('s.category LIKE :category')
            ->setParameter('category','%'.$category.'%')
            ->getQuery()->getResult();
    }
    // /**
    //  * @return Subject[] Returns an array of Subject objects
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
    public function findOneBySomeField($value): ?Subject
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
