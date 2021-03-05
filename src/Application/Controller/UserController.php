<?php

namespace App\Application\Controller;

use App\Application\MessageBus\QueryBus;
use App\Domain\Action\Command\User\UserCreateCommand;
use App\Domain\Action\Query\UserGetByIdQuery;
use Ramsey\Uuid\Uuid;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\HttpFoundation\Request;

class UserController extends ApiController
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

            $command = new UserCreateCommand(
                Uuid::uuid4(),
                $data['name'],
                $data['email'],
                $data['password'],
                $data['cpfCnpj'],
                $data['type']
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

    /**
     * @param int $id
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function getDataUser(int $id)
    {
        try {
            $query = new UserGetByIdQuery($id);
            $user = $this->queryBus->handle($query);

            return $this->response($user);
        } catch (\Exception $e) {
            return $this->respondWithExceptionError($e);
        }
    }
}