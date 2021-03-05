<?php

namespace App\Application\Controller;

use App\Application\MessageBus\QueryBus;
use App\Domain\Action\Command\User\UserCreateCommand;
use App\Domain\Infrastructure\Doctrine\Repository\UserRepository;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Ramsey\Uuid\Uuid;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class AuthController extends ApiController
{
    /**
     * @var MessageBusInterface
     */
    private $messageBus;

    /**
     * @var QueryBus
     */
    private $queryBus;

    /**
     * @var ValidatorInterface
     */
    private $validator;

    public function __construct(MessageBusInterface $messageBus, QueryBus $queryBus, ValidatorInterface $validator)
    {
        $this->messageBus = $messageBus;
        $this->queryBus = $queryBus;
        $this->validator = $validator;
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function register(Request $request)
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
     * @param Request $request
     * @param UserRepository $userRepository
     * @param UserPasswordEncoderInterface $encoder
     * @param JWTTokenManagerInterface $JWTManager
     * @return JsonResponse
     */
    public function login(Request $request, UserRepository $userRepository, UserPasswordEncoderInterface $encoder, JWTTokenManagerInterface $JWTManager)
    {
        $data = $this->parseJsonBody($request);

        $user = $userRepository->findOneBy([
            'email' => $data['email'],
        ]);

        if (!$user || !$encoder->isPasswordValid($user, $data['password'])) {
            return $this->respondWithErrors('e-mail ou senha estão incorretos!');
        }

        return new JsonResponse(['token' => $JWTManager->create($user)]);
    }
}