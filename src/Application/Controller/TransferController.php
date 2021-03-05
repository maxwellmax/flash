<?php

namespace App\Application\Controller;

use App\Application\MessageBus\QueryBus;
use App\Domain\Action\Command\Transfer\TransferTransactionCommand;
use Ramsey\Uuid\Uuid;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\HttpFoundation\Request;

class TransferController extends ApiController
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
    public function transaction(Request $request)
    {
        try {
            $data = $this->parseJsonBody($request);

            $command = new TransferTransactionCommand(
                Uuid::uuid4(),
                $data['value'],
                $data['payer'],
                $data['payee']
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
}