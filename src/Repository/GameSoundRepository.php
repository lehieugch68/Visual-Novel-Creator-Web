<?php

namespace App\Repository;

use App\Entity\GameSound;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method GameSound|null find($id, $lockMode = null, $lockVersion = null)
 * @method GameSound|null findOneBy(array $criteria, array $orderBy = null)
 * @method GameSound[]    findAll()
 * @method GameSound[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GameSoundRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, GameSound::class);
    }

    // /**
    //  * @return GameSound[] Returns an array of GameSound objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('g.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?GameSound
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
