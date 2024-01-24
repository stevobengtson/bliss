<?php

namespace App\Repository;

use App\Entity\Payee;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Payee>
 *
 * @method Payee|null find($id, $lockMode = null, $lockVersion = null)
 * @method Payee|null findOneBy(array $criteria, array $orderBy = null)
 * @method Payee[]    findAll()
 * @method Payee[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PayeeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Payee::class);
    }
}
