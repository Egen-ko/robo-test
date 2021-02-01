<?php

namespace App\Repository;

use App\Entity\PaymentTask;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method PaymentTask|null find($id, $lockMode = null, $lockVersion = null)
 * @method PaymentTask|null findOneBy(array $criteria, array $orderBy = null)
 * @method PaymentTask[]    findAll()
 * @method PaymentTask[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PaymentTaskRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PaymentTask::class);
    }

     /**
      * @return PaymentTask[] Returns an array of PaymentTask objects
      */
    public function findByScheduledFor(\DateTime $date)
    {
        /** @var \DateTime $date_from */
        $date_from = $this->getDateTimeWithoutMinutes($date);
        /** @var \DateTime $date_to */
        $date_to = (clone $date_from)->modify('+1 hour');

        return $this->createQueryBuilder('pt')
            ->andWhere('pt.scheduled_for >= :from')
            ->andWhere('pt.scheduled_for < :to')
            ->andWhere('pt.state = 0')
            ->setParameter('from', $date_from)
            ->setParameter('to', $date_to)
            ->orderBy('pt.scheduled_for', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }

    private function getDateTimeWithoutMinutes(\DateTime $dt): \DateTime
    {
        $h = (int)$dt->format('H');
        return $dt->setTime($h, 0);
    }
}
