<?php

namespace App\Repository;

use App\Entity\Vehicle;
use App\Entity\Categorie;
use Psr\Log\LoggerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\QueryBuilder;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @extends ServiceEntityRepository<Vehicle>
 *
 * @method Vehicle|null find($id, $lockMode = null, $lockVersion = null)
 * @method Vehicle|null findOneBy(array $criteria, array $orderBy = null)
 * @method Vehicle[]    findAll()
 * @method Vehicle[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VehicleRepository extends ServiceEntityRepository
{
    private $logger;

    public function __construct(ManagerRegistry $registry, LoggerInterface $logger)
    {
        parent::__construct($registry, Vehicle::class);
        $this->logger = $logger;
    }



    public function save(Vehicle $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Vehicle $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findByCategory(Categorie $category, int $page = 1, int $limit = 10)
    {
        $offset = ($page - 1) * $limit;
        return $this->createQueryBuilder('v')
            ->andWhere('v.categorie = :category')
            ->setParameter('category', $category)
            ->setMaxResults($limit)
            ->setFirstResult($offset)
            ->getQuery()
            ->getResult();
    }

   


    public function searchByYear(?string $category, $price, $year, $kilometer): array
    {
        $qb = $this->createQueryBuilder('v')
            ->leftJoin('v.categorie', 'c')
            ->select('v')
            ->where('1 = 1')
            ->leftJoin('v.images', 'i')
        ->addSelect('i');

        if ($category) {
            $qb->andWhere('c.name = :category')
                ->setParameter('category', $category);
        }

        if ($price) {
            $qb->andWhere('v.price <= :price')
                ->setParameter('price', $price);
        }

        if (!empty($year)) {
            $qb->andWhere('v.year >= :year')
                ->setParameter('year', $year);
        }

        if ($kilometer) {
            $qb->andWhere('v.kilometer <= :kilometer')
                ->setParameter('kilometer', $kilometer);
        }

        

        // Add sorting by year and price
        $qb->orderBy('v.year', 'DESC')
            ->addOrderBy('v.price', 'DESC');

        return $qb->getQuery()->getResult();
    }

    public function findByDisplayOnHomePage(bool $displayOnHomePage)
    {
        return $this->createQueryBuilder('v')
            ->innerJoin('v.images', 'i')
            ->where('i.displayOnHomePage = :displayOnHomePage')
            ->setParameter('displayOnHomePage', $displayOnHomePage)
            ->getQuery()
            ->getResult();
    }

}
