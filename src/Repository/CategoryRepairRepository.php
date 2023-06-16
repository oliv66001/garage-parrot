<?php

namespace App\Repository;

use App\Entity\Repair;
use App\Entity\CategoryRepair;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @extends ServiceEntityRepository<CategoryRepair>
 *
 * @method CategoryRepair|null find($id, $lockMode = null, $lockVersion = null)
 * @method CategoryRepair|null findOneBy(array $criteria, array $orderBy = null)
 * @method CategoryRepair[]    findAll()
 * @method CategoryRepair[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CategoryRepairRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CategoryRepair::class);
    }

    // The rest of your code...



    public function save(Repair $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Repair $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

  


    //    /**
    //     * @return Repair[] Returns an array of Repair objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('r')
    //            ->andWhere('r.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('r.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()

    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Repair
    //    {
    //        return $this->createQueryBuilder('r')
    //            ->andWhere('r.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
