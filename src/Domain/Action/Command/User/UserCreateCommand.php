<?php

namespace App\Domain\Action\Command\User;

use Ramsey\Uuid\UuidInterface;
use Symfony\Component\Validator\Constraints as Assert;

class UserCreateCommand
{
    /**
     * @var UuidInterface
     */
    private $uuid;

    /**
     * @var string
     * @Assert\NotBlank(message="Campo obrigatório")
     */
    private $name;

    /**
     * @var string
     * @Assert\Email(message="E-mail inválido.")
     */
    private $email;

    /**
     * @var string
     * @Assert\NotBlank(message="Campo obrigatório")
     */
    private $password;

    /**
     * @var string
     *  @Assert\NotBlank(message="Campo obrigatório")
     */
    private $cpfCnpj;

    /**
     * @var int
     *  @Assert\NotBlank(message="Campo obrigatório")
     */
    private $type;

    /**
     * @var bool
     */
    private $userAlreadyExists;

    /**
     * @var string|null
     */
    private $message;

    /**
     * CreateUserCommand constructor.
     * @param UuidInterface $uuid
     * @param string $name
     * @param string $email
     * @param string $password
     * @param string $cpfCnpj
     * @param int $type
     */
    public function __construct(
        UuidInterface $uuid,
        string $name,
        string $email,
        string $password,
        string $cpfCnpj,
        int $type
    ) {
        $this->uuid = $uuid;
        $this->name = $name;
        $this->email = $email;
        $this->password = $password;
        $this->cpfCnpj = $cpfCnpj;
        $this->type = $type;
        $this->userAlreadyExists = false;
    }

    /**
     * @return UuidInterface
     */
    public function getUuid(): UuidInterface
    {
        return $this->uuid;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @return string
     */
    public function getCpfCnpj(): string
    {
        return $this->cpfCnpj;
    }

    /**
     * @return int
     */
    public function getType(): int
    {
        return $this->type;
    }

    /**
     * @return string|null
     */
    public function getMessage(): ?string
    {
        return $this->message;
    }

    /**
     * @param string|null $message
     */
    public function setMessage(?string $message): void
    {
        $this->message = $message;
    }
}