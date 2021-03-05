<?php

namespace App\Domain\Repository;


use App\Domain\Entity\Transfer;

interface TransferRepositoryInterface
{
    /**
     * @param int $id
     * @param null $lockMode
     * @param null $lockVersion
     * @return Transfer
     */
    public function find(int $id, $lockMode = null, $lockVersion = null);

    /**
     * @param array $filter
     * @param array|null $orderBy
     * @return mixed
     */
    public function findOneBy(array $filter, ?array $orderBy = null);

    /**
     * @param Transfer $transfer
     * @return mixed
     */
    public function save(Transfer $transfer);

    /**
     * @return mixed
     */
    public function update();
}