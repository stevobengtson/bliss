<?php

namespace App\Repository;

use App\Entity\GategoryGroup;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<GategoryGroup>
 *
 * @method GategoryGroup|null find($id, $lockMode = null, $lockVersion = null)
 * @method GategoryGroup|null findOneBy(array $criteria, array $orderBy = null)
 * @method GategoryGroup[]    findAll()
 * @method GategoryGroup[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GategoryGroupRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, GategoryGroup::class);
    }

//    /**
//     * @return GategoryGroup[] Returns an array of GategoryGroup objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('g')
//            ->andWhere('g.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('g.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?GategoryGroup
//    {
//        return $this->createQueryBuilder('g')
//            ->andWhere('g.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
