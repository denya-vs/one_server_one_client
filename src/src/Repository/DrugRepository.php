<?php

namespace App\Repository;

use App\Entity\Drug;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\Query;

/**
 * @method Drug|null find($id, $lockMode = null, $lockVersion = null)
 * @method Drug|null findOneBy(array $criteria, array $orderBy = null)
 * @method Drug[]    findAll()
 * @method Drug[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DrugRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Drug::class);
    }

     /**
      * @return Drug[] Returns an array of Drug objects
      */
    public function findByDisease($value)
    {
        return $this->createQueryBuilder('d')
            ->join('d.diseases', 'di')
            ->where('di.id = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getResult(Query::HYDRATE_ARRAY)
        ;
    }

    /*
    public function findOneBySomeField($value): ?Drug
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
