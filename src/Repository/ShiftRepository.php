<?php

namespace App\Repository;

use App\Entity\Shift;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Shift>
 *
 * @method Shift|null find($id, $lockMode = null, $lockVersion = null)
 * @method Shift|null findOneBy(array $criteria, array $orderBy = null)
 * @method Shift[]    findAll()
 * @method Shift[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ShiftRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Shift::class);
    }

//    /**
//     * @return Shift[] Returns an array of Shift objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('s.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Shift
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }

    public function findByWeek(int $week): array
    {
        $year = (int) date('Y');

        $monday = new \DateTime();
        $monday->setISODate($year, $week, 1)->setTime(0, 0, 0);

        $sunday = clone $monday;
        $sunday->modify('+6 days')->setTime(23, 59, 59);

        return $this->createQueryBuilder('s')
            ->where('s.startTime BETWEEN :start AND :end')
            ->setParameter('start', $monday)
            ->setParameter('end', $sunday)
            ->orderBy('s.startTime', 'ASC')
            ->getQuery()
            ->getResult();
    }

}
