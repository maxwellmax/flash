<?php

namespace App\Domain\Repository;

use App\Domain\Entity\Transfer;

interface TransferStatusRepositoryInterface
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
}