<?php

namespace App\Repository;

use App\Entity\ClientBalance;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ClientBalance|null find($id, $lockMode = null, $lockVersion = null)
 * @method ClientBalance|null findOneBy(array $criteria, array $orderBy = null)
 * @method ClientBalance[]    findAll()
 * @method ClientBalance[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ClientBalanceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ClientBalance::class);
    }
}
