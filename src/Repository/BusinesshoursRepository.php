<?php

namespace App\Repository;

use App\Entity\BusinessHours;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<BusinessHours>
 *
 * @method BusinessHours|null find($id, $lockMode = null, $lockVersion = null)
 * @method BusinessHours|null findOneBy(array $criteria, array $orderBy = null)
 * @method BusinessHours[]    findAll()
 * @method BusinessHours[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BusinessHoursRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Businesshours::class);
    }

    public function save(BusinessHours $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(BusinessHours $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
    
    public function findAllOrderedByDay(): array
    {
        return $this->createQueryBuilder('b')
            ->orderBy('b.day', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }
    
}
