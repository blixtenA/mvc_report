<?php

namespace App\Repository;

use App\Entity\Game;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\Query\Expr;
use Doctrine\ORM\AbstractQuery;

/**
 * @extends ServiceEntityRepository<Game>
 *
 * @method Game|null find($id, $lockMode = null, $lockVersion = null)
 * @method Game|null findOneBy(array $criteria, array $orderBy = null)
 * @method Game[]    findAll()
 * @method Game[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GameRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Game::class);
    }

    /**
     * @return int[]
     */
    public function findUniqueGameIds(): array
    {
        $queryBuilder = $this->createQueryBuilder('g')
            ->select('g.game_id AS gameId')
            ->groupBy('gameId');
    
        $query = $queryBuilder->getQuery();
    
        $results = $query->getResult();

        $gameIds = [];
        foreach ($results as $result) {
            $gameIds[] = $result['gameId'];
        }
    
        return $gameIds;
    }

    public function save(Game $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Game $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findGameByCoordinates(int $gameId, int $x, int $y): ?Game
    {
        $queryBuilder = $this->createQueryBuilder('g')
            ->where('g.game_id = :gameId')
            ->andWhere('g.pos_x = :x')
            ->andWhere('g.pos_y = :y')
            ->setParameters([
                'gameId' => $gameId,
                'x' => $x,
                'y' => $y,
            ]);
    
        $query = $queryBuilder->getQuery();
    
        $result = $query->getResult();
        dump($result); // Output the query result for debugging purposes
    
        return $result[0] ?? null;
    }
    
    


//    /**
//     * @return Game[] Returns an array of Game objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('g')
//            ->andWhere('g.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('g.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Game
//    {
//        return $this->createQueryBuilder('g')
//            ->andWhere('g.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
