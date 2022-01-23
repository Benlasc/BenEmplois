<?php

namespace App\Repository;

use App\Entity\Job;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Job|null find($id, $lockMode = null, $lockVersion = null)
 * @method Job|null findOneBy(array $criteria, array $orderBy = null)
 * @method Job[]    findAll()
 * @method Job[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class JobRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Job::class);
    }

    public function findWithFilter(array $cities, array $contractTypes, array $contractNatures)
    {
        $qb = $this->createQueryBuilder('j');

        if ($cities != false) {
            $qb->where($qb->expr()->in('j.city', ':cities'))
                ->setParameter(':cities', $cities);
        }

        if ($contractTypes != false) {
            $qb->andWhere($qb->expr()->in('j.contractType', ':contractTypes'))
                ->setParameter(':contractTypes', $contractTypes);
        }

        if ($contractNatures != false) {
            $qb->andWhere($qb->expr()->in('j.contractNature', ':contractNatures'))
                ->setParameter(':contractNatures', $contractNatures);
        }

        $qb->orderBy('j.updateDate', 'DESC');

        return $qb->getQuery()->getResult();
    }

    // /**
    //  * @return Job[] Returns an array of Job objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('j')
            ->andWhere('j.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('j.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Job
    {
        return $this->createQueryBuilder('j')
            ->andWhere('j.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
