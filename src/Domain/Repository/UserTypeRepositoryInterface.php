<?php

namespace App\Domain\Repository;

use App\Domain\Entity\UserType;

interface UserTypeRepositoryInterface
{
    /**
     * @param int $id
     * @param null $lockMode
     * @param null $lockVersion
     * @return UserType
     */
    public function find(int $id, $lockMode = null, $lockVersion = null);
}