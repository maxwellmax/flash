<?php

namespace App\Domain\Action\Command\Wallet\Handler;

use App\Domain\Action\Command\Wallet\WalletUpdateCommand;
use App\Domain\Entity\Wallet;
use App\Domain\Exception\DomainException;
use App\Domain\Repository\WalletRepositoryInterface;

class WalletUpdateCommandHandler
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
     * @param WalletUpdateCommand $command
     * @throws \Exception
     */
    public function __invoke(WalletUpdateCommand $command)
    {
        /** @var Wallet $wallet */
        $wallet = $this->walletRepository->find($command->getId());
        if (!$wallet) {
            throw new DomainException('Carteira não encontrada.', 404);
        }

        $wallet->put($command->getBalance());
        $this->walletRepository->update();

        $command->setMessage('Carteira atualizada com sucesso.');
    }
}