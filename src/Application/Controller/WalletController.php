<?php

namespace App\Application\Controller;

use App\Application\MessageBus\QueryBus;
use App\Domain\Action\Command\Wallet\WalletCreateCommand;
use App\Domain\Action\Command\Wallet\WalletUpdateCommand;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\HttpFoundation\Request;

class WalletController extends ApiController
{
    /**
     * @var MessageBusInterface
     */
    private $messageBus;

    /**
     * @var ValidatorInterface
     */
    private $validator;

    /**
     * @var QueryBus
     */
    private $queryBus;

    public function __construct(MessageBusInterface $messageBus, QueryBus $queryBus, ValidatorInterface $validator)
    {
        $this->messageBus = $messageBus;
        $this->validator = $validator;
        $this->queryBus = $queryBus;
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function create(Request $request)
    {
        try {
            $data = $this->parseJsonBody($request);

            $command = new WalletCreateCommand(
                $data['balance']
            );

            $errors = $this->validator->validate($command);
            if (count($errors) > 0) {
                return $this->respondWithValidationErrors($errors);
            }

            $this->messageBus->dispatch($command);

            return $this->respondWithSuccess($command->getMessage());
        } catch (\Exception $e) {
            return $this->respondWithExceptionError($e);
        }
    }

    public function put(int $id, Request $request)
    {
        try {
            $data = $this->parseJsonBody($request);

            $command = new WalletUpdateCommand(
                $id,
                $data['balance']
            );

            $this->messageBus->dispatch($command);

            return $this->respondWithSuccess($command->getMessage());
        } catch (\Exception $e) {
            return $this->setStatusCode($this->getStatusCodeError($e->getCode()))
                ->respondWithExceptionError($e);
        }
    }
}