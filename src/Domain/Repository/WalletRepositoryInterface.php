<?php

namespace App\Domain\Repository;


use App\Domain\Entity\Wallet;

interface WalletRepositoryInterface
{
    /**
     * @param int $id
     * @param null $lockMode
     * @param null $lockVersion
     * @return Wallet
     */
    public function find(int $id, $lockMode = null, $lockVersion = null);

    /**
     * @param array $filter
     * @param array|null $orderBy
     * @return mixed
     */
    public function findOneBy(array $filter, ?array $orderBy = null);

    /**
     * @param Wallet $wallet
     * @return mixed
     */
    public function save(Wallet $wallet);

    /**
     * @return mixed
     */
    public function update();
}