<?php

namespace App\Domain\Infrastructure\Doctrine\Repository;

use App\Domain\Entity\Transferstatus;
use App\Domain\Repository\TransferStatusRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Transferstatus|null find($id, $lockMode = null, $lockVersion = null)
 * @method Transferstatus|null findOneBy(array $criteria, array $orderBy = null)
 * @method Transferstatus[]    findAll()
 * @method Transferstatus[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TransferStatusRepository extends ServiceEntityRepository implements TransferStatusRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Transferstatus::class);
    }

    /**
     * @param Transferstatus $transferStatus
     * @return mixed|void
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function save(Transferstatus $transferStatus)
    {
        $this->getEntityManager()->persist($transferStatus);
        $this->getEntityManager()->flush();
    }

    /**
     * @return mixed|void
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function update()
    {
        $this->getEntityManager()->flush();
    }
}
