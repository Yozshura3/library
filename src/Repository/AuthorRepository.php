<?php

namespace App\Repository;

use App\Entity\Author;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Author|null find($id, $lockMode = null, $lockVersion = null)
 * @method Author|null findOneBy(array $criteria, array $orderBy = null)
 * @method Author[]    findAll()
 * @method Author[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AuthorRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Author::class);
    }

    public function searchMotor()
    {
        $queryBuilder = $this->createQueryBuilder('a');

        $word = 'games';

        $query = $queryBuilder->select('a')
            ->where('a.biography LIKE :word')
            ->setParameter('word', '%'.$word.'%')
            ->getQuery();


        $biography = $query->getArrayResult();

        return $biography;
    }

    public  function getAuthorByName($word)
    {
        $qb = $this->createQueryBuilder('a');

        $query = $qb->select('a')
            ->where('a.name LIKE :word')
            ->setParameter('word', '%'.$word.'%')
            ->getQuery();
        $auteurs = $query->getArrayResult();

        return $auteurs;
    }

    // /**
    //  * @return Author[] Returns an array of Author objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Author
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
