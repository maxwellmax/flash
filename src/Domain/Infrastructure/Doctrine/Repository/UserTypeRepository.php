<?php

namespace App\Domain\Infrastructure\Doctrine\Repository;

use App\Domain\Entity\UserType;
use App\Domain\Repository\UserTypeRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method UserType|null find($id, $lockMode = null, $lockVersion = null)
 */
class UserTypeRepository extends ServiceEntityRepository implements UserTypeRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserType::class);
    }
}
