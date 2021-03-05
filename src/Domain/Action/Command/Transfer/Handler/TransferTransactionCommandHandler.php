<?php

namespace App\Domain\Action\Command\Transfer\Handler;

use App\Domain\Action\Command\Transfer\TransferTransactionCommand;
use App\Domain\Entity\Transfer;
use App\Domain\Entity\Transferstatus;
use App\Domain\Entity\User;
use App\Domain\Entity\UserType;
use App\Domain\Entity\Wallet;
use App\Domain\Exception\DomainException;
use App\Domain\Repository\TransferRepositoryInterface;
use App\Domain\Repository\TransferStatusRepositoryInterface;
use App\Domain\Repository\UserRepositoryInterface;
use App\Domain\Repository\WalletRepositoryInterface;

class TransferTransactionCommandHandler
{
    /**
     * @var TransferRepositoryInterface
     */
    private $transferRepository;

    /**
     * @var TransferStatusRepositoryInterface
     */
    private $transferStatusRepository;

    /**
     * @var UserRepositoryInterface
     */
    private $userRepository;

    /**
     * @var WalletRepositoryInterface
     */
    private $walletRepository;

    public function __construct(
        TransferRepositoryInterface $transferRepository,
        TransferStatusRepositoryInterface $transferStatusRepository,
        UserRepositoryInterface $userRepository,
        WalletRepositoryInterface $walletRepository
    ) {
        $this->transferRepository = $transferRepository;
        $this->transferStatusRepository = $transferStatusRepository;
        $this->userRepository = $userRepository;
        $this->walletRepository = $walletRepository;
    }

    /**
     * @param TransferTransactionCommand $command
     * @throws \Exception
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function __invoke(TransferTransactionCommand $command)
    {
        /** @var User $payer */
        $payer = $this->userRepository->find($command->getPayer());
        if (!$payer) {
            throw new DomainException('Usuário pagador não encontrado.');
        }

        /** @var User $payee */
        $payee = $this->userRepository->find($command->getPayee());
        if (!$payee) {
            throw new DomainException('Usuário beneficiário não encontrado.');
        }

        if ($payer->getUserType()->getId() === UserType::USER_TYPE_LOJISTA) {
            throw new DomainException('Usuário não tem permisso para transferencia.');
        }

        /** @var Transferstatus $transferStatus */
        $transferStatus = $this->transferStatusRepository->find(Transferstatus::TRANSFER_STATUS_AUTORIZADO);

        $transfer = new Transfer(
            $command->getUuid()->toString(),
            $command->getValue(),
            $transferStatus,
            $payer,
            $payee
        );

        if ($this->authorizerTransfer()) {
            $this->updateWalletBalance($payee->getWallet(), $command->getValue());
            $this->transferRepository->save($transfer);
        }

        $command->setMessage('Transferência realizada com sucesso.');
    }

    /**
     * @return bool
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    private function authorizerTransfer()
    {
        $client = new \GuzzleHttp\Client();
        $response = $client->request('POST', 'https://run.mocky.io/v3/8fafdd68-a090-496f-8c9a-3442cf30dae6');

        $message = json_decode($response->getBody()->getContents(), true);

        if ($message['message'] !== 'Autorizado') {
            return false;
        }

        return true;
    }

    /**
     * @param Wallet $wallet
     * @param $value
     * @throws \Exception
     */
    private function updateWalletBalance(Wallet $wallet, $value)
    {
        $value = str_replace(array('.', ','), array('', '.'), $value);
        $balance = str_replace(array('.', ','), array('', '.'), $wallet->getBalance());

        $balanceTotal = ($value + $balance);

        $wallet->put($balanceTotal);
        $this->walletRepository->update();
    }
}