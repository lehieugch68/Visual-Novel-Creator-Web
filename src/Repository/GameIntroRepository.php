<?php

namespace App\Repository;

use App\Entity\GameIntro;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method GameIntro|null find($id, $lockMode = null, $lockVersion = null)
 * @method GameIntro|null findOneBy(array $criteria, array $orderBy = null)
 * @method GameIntro[]    findAll()
 * @method GameIntro[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GameIntroRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, GameIntro::class);
    }

    // /**
    //  * @return GameIntro[] Returns an array of GameIntro objects
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
    public function findOneBySomeField($value): ?GameIntro
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
        $sql = 'SELECT MAX(introorder) AS LargestOrder FROM game_intro AS g WHERE g.game_id = :gameid LIMIT 1';
        $stmt = $conn->prepare($sql);
        $stmt->execute(['gameid' => $gameId]);
        return $stmt->fetchAllAssociative();
    }
}
