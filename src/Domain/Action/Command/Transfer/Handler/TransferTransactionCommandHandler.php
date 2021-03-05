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

        if (!$this->hasBalanceToTransaction($payer->getWallet()->getBalance(), $command->getValue())) {
            throw new DomainException('Usuário não tem saldo suficiente');
        }

        try {
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
                $balanceTotalPayee = $this->calculeBalanceTotal($payee->getWallet()->getBalance(), $command->getValue(), '+');
                $balanceTotalPayer = $this->calculeBalanceTotal($payer->getWallet()->getBalance(), $command->getValue(), '-');

                $payee->getWallet()->put($balanceTotalPayee);
                $payer->getWallet()->put($balanceTotalPayer);
                $this->walletRepository->update();
            } else {
                /** @var Transferstatus $transferStatus */
                $transferStatus = $this->transferStatusRepository->find(Transferstatus::TRANSFER_STATUS_ESTORNADO);
                $transfer->changeTransferStatus($transferStatus);
            }


            $this->transferRepository->save($transfer);

            $command->setMessage('Transferência realizada com sucesso.');
        } catch (\Exception $e) {
            throw $e;
        }
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

    private function calculeBalanceTotal($balance, $value, $operator)
    {
        $value = str_replace(array(',', '.'), array('', '.'), $value);
        $balance = str_replace(array(',', '.'), array('', '.'), $balance);

        $total = 0;
        switch ($operator) {
            case '-':
                $total = $balance - $value;
                break;
            case '+':
                $total =  $balance + $value;
                break;
        }

        return number_format($total, 2);
    }

    /**
     * @param $balance
     * @param $value
     * @return bool
     */
    private function hasBalanceToTransaction($balance, $value)
    {
        if (strval($balance) >= strval($value)) {
            return true;
        }

        return false;
    }
}