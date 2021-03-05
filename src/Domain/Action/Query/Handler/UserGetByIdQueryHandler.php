<?php

namespace App\Domain\Action\Query\Handler;

use App\Domain\Action\Query\UserGetByIdQuery;
use App\Domain\Repository\UserRepositoryInterface;
use Doctrine\ORM\EntityNotFoundException;

class UserGetByIdQueryHandler
{
    /** @var UserRepositoryInterface */
    private $userRepository;

    /**
     * UserGetByIdQueryHandler constructor.
     * @param UserRepositoryInterface $userRepository
     */
    public function __construct(
        UserRepositoryInterface $userRepository
    )
    {
        $this->userRepository = $userRepository;
    }

    /**
     * @param UserGetByIdQuery $query
     * @return array
     * @throws EntityNotFoundException
     */
    public function __invoke(UserGetByIdQuery $query)
    {
        $user = $this->userRepository->find($query->getUser());

        if (!$user) {
            throw new EntityNotFoundException('Usuário não encontrado', 404);
        }

        return [
            'id' => $user->getId(),
            "uuid" => $user->getUuid()->toString(),
            "name" => $user->getName(),
            "email" => $user->getEmail(),
            "cpf_cnpj" => $user->getCpfCnpj(),
            "type" => $user->getType()->getType(),
            "createdAt" => $user->getCreatedAt()->format('Y-m-d H:i:s'),
            "updatedAt" => $user->getUpdatedAt()->format('Y-m-d H:i:s')
        ];
    }
}