<?php

namespace App\Repository;

use App\Entity\GameStoryScene;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method GameStoryScene|null find($id, $lockMode = null, $lockVersion = null)
 * @method GameStoryScene|null findOneBy(array $criteria, array $orderBy = null)
 * @method GameStoryScene[]    findAll()
 * @method GameStoryScene[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GameStorySceneRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, GameStoryScene::class);
    }

    // /**
    //  * @return GameStoryScene[] Returns an array of GameStoryScene objects
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
    public function findOneBySomeField($value): ?GameStoryScene
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    public function findLargestOrder(int $sceneid): array
    {
        $conn = $this->getEntityManager()->getConnection();
        $sql = 'SELECT MAX(contextorder) AS LargestOrder FROM game_story_scene AS g WHERE g.gamescene_id = :sceneid LIMIT 1';
        $stmt = $conn->prepare($sql);
        $stmt->execute(['sceneid' => $sceneid]);
        return $stmt->fetchAllAssociative();
    }
}
