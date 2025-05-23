<?php

namespace App\Repository;

use App\Entity\Partie;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Partie>
 */
class PartieRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Partie::class);
    }

    public function findScoresByGameCurrentMonth($jeu)      
    {   
        $date = new \DateTime();
        $date->modify('first day of this month');
        $date->setTime(0, 0, 0); // Set time to midnight to include all scores from the first day of the month
        $date2 = new \DateTime();
        $date2->modify('last day of this month');
        $date2->setTime(23, 59, 59); // Set time to the end of the last day of the month


        return $this->createQueryBuilder('p')
            ->andWhere('p.jeu = :jeu')
            ->andWhere('p.date BETWEEN :date AND :date2')
            ->setParameter('jeu', $jeu)
            ->setParameter('date', $date)
            ->setParameter('date2', $date2)
            ->orderBy('p.score', 'DESC')
            ->getQuery()
            ->getResult();
    }

//    /**
//     * @return Partie[] Returns an array of Partie objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('p.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Partie
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
