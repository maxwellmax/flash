<?php

namespace App\Domain\Action\Command\Wallet\Handler;


use App\Domain\Action\Command\Wallet\WalletCreateCommand;
use App\Domain\Entity\Wallet;
use App\Domain\Repository\WalletRepositoryInterface;

class WalletCreateCommandHandler
{

    /**
     * @var WalletRepositoryInterface
     */
    private $walletRepository;

    public function __construct(
        WalletRepositoryInterface $walletRepository
    ) {
        $this->walletRepository = $walletRepository;
    }

    /**
     * @param WalletCreateCommand $command
     * @throws \Exception
     */
    public function __invoke(WalletCreateCommand $command)
    {
        $wallet = new Wallet(
            $command->getBalance()
        );

        $this->walletRepository->save($wallet);

        $command->setMessage('Carteira criada com sucesso.');
    }
}