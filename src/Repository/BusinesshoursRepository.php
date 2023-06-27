<?php

namespace App\Repository;

use App\Entity\Businesshours;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Businesshours>
 *
 * @method Businesshours|null find($id, $lockMode = null, $lockVersion = null)
 * @method Businesshours|null findOneBy(array $criteria, array $orderBy = null)
 * @method Businesshours[]    findAll()
 * @method Businesshours[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BusinesshoursRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Businesshours::class);
    }

    public function save(Businesshours $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Businesshours $entity, bool $flush = false): void
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
