<?php

namespace App\Domain\Action\Command\User\Handler;

use App\Domain\Action\Command\User\UserCreateCommand;
use App\Domain\Entity\User;
use App\Domain\Entity\UserType;
use App\Domain\Entity\Wallet;
use App\Domain\Exception\DomainException;
use App\Domain\Repository\UserRepositoryInterface;
use App\Domain\Repository\UserTypeRepositoryInterface;
use App\Domain\Repository\WalletRepositoryInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserCreateCommandHandler
{

    /**
     * @var UserRepositoryInterface
     */
    private $userRepository;

    /**
     * @var UserTypeRepositoryInterface
     */
    private $userTypeRepository;

    /**
     * @var WalletRepositoryInterface
     */
    private $walletRepository;

    /**
     * @var UserPasswordEncoderInterface
     */
    private $encoder;

    public function __construct(
        UserRepositoryInterface $userRepository,
        UserTypeRepositoryInterface $userTypeRepository,
        WalletRepositoryInterface $walletRepository,
        UserPasswordEncoderInterface $encoder
    ) {
        $this->userRepository = $userRepository;
        $this->userTypeRepository = $userTypeRepository;
        $this->walletRepository = $walletRepository;
        $this->encoder = $encoder;
    }

    /**
     * @param UserCreateCommand $command
     * @throws \Exception
     */
    public function __invoke(UserCreateCommand $command)
    {

        /** @var User $user */
        $user = $this->userRepository->findOneBy(['email' => $command->getEmail()]);
        if (null !== $user) {
            throw new DomainException('Já existe um usuário para este e-mail.');
        }

        /** @var User $user */
        $user = $this->userRepository->findOneBy(['cpfCnpj' => $command->getCpfCnpj()]);
        if (null !== $user) {
            throw new DomainException('Já existe um usuário para CPF/CNPJ.');
        }

        /** @var UserType $type */
        $type = $this->userTypeRepository->find($command->getType());

        if (!$type) {
            throw new DomainException('Tipo de usuário incorreto');
        }

        $wallet = new Wallet(0);
        $this->walletRepository->save($wallet);

        $user = new User(
            $command->getUuid()->toString(),
            $command->getName(),
            $command->getEmail(),
            $command->getPassword(),
            $command->getCpfCnpj(),
            $type,
            $wallet
        );

        $this->encriptyPassword($user);

        $this->userRepository->save($user);

        $command->setMessage('Usuário criado com sucesso.');
    }

    /**
     * @param User $user
     */
    private function encriptyPassword(User $user): void
    {
        if (null !== $user->getPassword()) {
            $user->changePassword($this->encoder->encodePassword($user, $user->getPassword()));
        }
    }
}