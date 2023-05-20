<?php

namespace App\Repository;

use App\Entity\EventByObject;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<EventByObject>
 *
 * @method EventByObject|null find($id, $lockMode = null, $lockVersion = null)
 * @method EventByObject|null findOneBy(array $criteria, array $orderBy = null)
 * @method EventByObject[]    findAll()
 * @method EventByObject[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EventByObjectRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, EventByObject::class);
    }

    public function save(EventByObject $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(EventByObject $entity, bool $flush = false): void
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
    public function findEventIDsByObjectID(int $objectID): array
    {
        $qb = $this->createQueryBuilder('ebo');
        $qb->select('ebo.event_id')
            ->where('ebo.object_id = :objectID')
            ->setParameter('objectID', $objectID);
    
        $query = $qb->getQuery();
        $result = $query->getResult();
    
        // Extract event IDs from the result
        $eventIDs = array_column($result, 'event_id');
    
        return $eventIDs;
    }    

    /**
 * @param int $objectID
 * @param int $locationID
 * @return int[]
 */
    public function findEventIDsByObjectIDAndLocation(int $objectID, int $locationID): array
    {
        $entityManager = $this->getEntityManager();
    
        $query = $entityManager->createQuery('
            SELECT ebo.event_id
            FROM App\Entity\EventByObject ebo
            WHERE ebo.object_id = :objectID
            AND ebo.location = :locationID
        ');
    
        $query->setParameter('objectID', $objectID);
        $query->setParameter('locationID', $locationID);
    
        $result = $query->getResult();
    
        // Extract the event IDs from the query result
        $eventIDs = array_column($result, 'event_id');
    
        return $eventIDs;
    }
    

//    /**
//     * @return EventByObject[] Returns an array of EventByObject objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('e')
//            ->andWhere('e.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('e.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?EventByObject
//    {
//        return $this->createQueryBuilder('e')
//            ->andWhere('e.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
