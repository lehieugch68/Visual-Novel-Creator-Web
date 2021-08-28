<?php

namespace App\Repository;

use App\Entity\GameScene;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method GameScene|null find($id, $lockMode = null, $lockVersion = null)
 * @method GameScene|null findOneBy(array $criteria, array $orderBy = null)
 * @method GameScene[]    findAll()
 * @method GameScene[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GameSceneRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, GameScene::class);
    }

    // /**
    //  * @return GameScene[] Returns an array of GameScene objects
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
    public function findOneBySomeField($value): ?GameScene
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    public function findLargestOrder(int $gameId): array
    {
        $conn = $this->getEntityManager()->getConnection();
        $sql = 'SELECT MAX(sceneorder) AS LargestOrder FROM game_scene AS g WHERE g.game_id = :gameid LIMIT 1';
        $stmt = $conn->prepare($sql);
        $stmt->execute(['gameid' => $gameId]);
        return $stmt->fetchAllAssociative();
    }
}
