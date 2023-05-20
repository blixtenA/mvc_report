<?php

namespace App\Repository;

use App\Entity\ObjectByRoom;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ObjectByRoom>
 *
 * @method ObjectByRoom|null find($id, $lockMode = null, $lockVersion = null)
 * @method ObjectByRoom|null findOneBy(array $criteria, array $orderBy = null)
 * @method ObjectByRoom[]    findAll()
 * @method ObjectByRoom[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ObjectByRoomRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ObjectByRoom::class);
    }

    public function save(ObjectByRoom $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(ObjectByRoom $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * @param int $objectID
     * @return int[]
     */
    public function findSequenceByObjectID(int $objectID): array
    {
        return $this->createQueryBuilder('obr')
            ->select('obr.sequence')
            ->where('obr.object_id = :objectID')
            ->setParameter('objectID', $objectID)
            ->getQuery()
            ->getResult();
    }

    public function findOneBy(array $criteria, array $orderBy = null)
    {
        $qb = $this->createQueryBuilder('obr');
    
        foreach ($criteria as $field => $value) {
            $qb->andWhere('obr.' . $field . ' = :' . $field)
                ->setParameter($field, $value);
        }
    
        return $qb->getQuery()->getOneOrNullResult();
    }

//    /**
//     * @return ObjectByRoom[] Returns an array of ObjectByRoom objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('o')
//            ->andWhere('o.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('o.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?ObjectByRoom
//    {
//        return $this->createQueryBuilder('o')
//            ->andWhere('o.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
