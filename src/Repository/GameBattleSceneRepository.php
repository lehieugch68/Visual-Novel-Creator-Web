<?php

namespace App\Repository;

use App\Entity\GameBattleScene;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method GameBattleScene|null find($id, $lockMode = null, $lockVersion = null)
 * @method GameBattleScene|null findOneBy(array $criteria, array $orderBy = null)
 * @method GameBattleScene[]    findAll()
 * @method GameBattleScene[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GameBattleSceneRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, GameBattleScene::class);
    }

    // /**
    //  * @return GameBattleScene[] Returns an array of GameBattleScene objects
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
    public function findOneBySomeField($value): ?GameBattleScene
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
