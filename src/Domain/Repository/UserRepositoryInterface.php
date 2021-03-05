<?php

namespace App\Domain\Repository;

use App\Domain\Entity\User;

interface UserRepositoryInterface
{
    /**
     * @param int $id
     * @param null $lockMode
     * @param null $lockVersion
     * @return User
     */
    public function find(int $id, $lockMode = null, $lockVersion = null);

    /**
     * @param array $filter
     * @param array|null $orderBy
     * @return mixed
     */
    public function findOneBy(array $filter, ?array $orderBy = null);

    public function getUsers();

    /**
     * @param User $user
     * @return mixed
     */
    public function save(User $user);

    /**
     * @return mixed
     */
    public function update();

    /**
     * @param int $id
     * @param string $email
     * @return mixed
     */
    public function checkUserEmail(int $id, string $email);
}