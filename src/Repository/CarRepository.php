<?php

namespace App\Repository;

use App\Entity\Car;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\Query;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Car>
 */
class CarRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Car::class);
    }

    public function save(Car $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);
        if ($flush){
            $this->getEntityManager()->flush();
        }
    }
    public function remove(Car $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);
        
        if ($flush){
            $this->getEntityManager()->flush();
        }
    }

    public function getAdminList(string $query): array
    {
        $qb = $this->createQueryBuilder('c')
            ->orderBy('c.year', 'DESC');

        if (!empty($query)) {
            $query = "%".strtolower($query)."%";

            $qb
                ->orWhere('LOWER(c.brand) LIKE :query')
                ->orWhere('LOWER(c.model) LIKE :query')
                ->setParameter('query', $query);
        }

        return $qb->getQuery()->getResult();
    }



    
}
