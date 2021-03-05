<?php

namespace App\Domain\Action\Query;

class UserGetByIdQuery
{
    /**
     * @var int
     */
    private $user;

    /**
     * GetUserQuery constructor.
     * @param int $user
     */
    public function __construct(int $user)
    {
        $this->user = $user;
    }

    /**
     * @return int
     */
    public function getUser(): int
    {
        return $this->user;
    }
}