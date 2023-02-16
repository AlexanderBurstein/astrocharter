<?php

namespace App\Repository;

use App\Entity\Person;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Person|null find($id, $lockMode = null, $lockVersion = null)
 * @method Person|null findOneBy(array $criteria, array $orderBy = null)
 * @method Person[]    findAll()
 * @method Person[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PersonRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Person::class);
    }

    public function isAlreadyStored($value, $oldId = 0)
    {
        return (!empty($this->createQueryBuilder('p')
            ->andWhere('p.fullname = :val1')
            ->andWhere('p.id != :val2' )
            ->setParameter('val1', $value)
            ->setParameter('val2', $oldId)
            ->getQuery()
            ->getOneOrNullResult()));
    }
}
