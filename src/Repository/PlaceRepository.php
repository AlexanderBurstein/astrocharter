<?php

namespace App\Repository;

use App\Entity\Place;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class PlaceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Place::class);
    }
    public function getAll()
    {
        return $this->createQueryBuilder('p')
            ->orderBy('p.id', 'ASC')
            ->getQuery()
            ->getResult()
            ;
    }
    public function isAlreadyStored($value, $oldId = 0)
    {
        return (!empty($this->createQueryBuilder('p')
            ->andWhere('p.placename = :val1')
            ->andWhere('p.id != :val2' )
            ->setParameter('val1', $value)
            ->setParameter('val2', $oldId)
            ->getQuery()
            ->getOneOrNullResult()));
    }
}
