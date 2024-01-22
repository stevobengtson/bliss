<?php

namespace App\Repository;

use App\Entity\CategoryGroup;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<CategoryGroup>
 *
 * @method CategoryGroup|null find($id, $lockMode = null, $lockVersion = null)
 * @method CategoryGroup|null findOneBy(array $criteria, array $orderBy = null)
 * @method CategoryGroup[]    findAll()
 * @method CategoryGroup[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CategoryGroupRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CategoryGroup::class);
    }
}
