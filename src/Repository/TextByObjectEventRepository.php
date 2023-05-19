<?php

namespace App\Repository;

use App\Entity\TextByObjectEvent;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<TextByObjectEvent>
 *
 * @method TextByObjectEvent|null find($id, $lockMode = null, $lockVersion = null)
 * @method TextByObjectEvent|null findOneBy(array $criteria, array $orderBy = null)
 * @method TextByObjectEvent[]    findAll()
 * @method TextByObjectEvent[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TextByObjectEventRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TextByObjectEvent::class);
    }

    public function save(TextByObjectEvent $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(TextByObjectEvent $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return TextByObjectEvent[] Returns an array of TextByObjectEvent objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('t.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?TextByObjectEvent
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
