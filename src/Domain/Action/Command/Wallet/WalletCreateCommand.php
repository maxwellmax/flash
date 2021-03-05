<?php

namespace App\Domain\Action\Command\Wallet;

use Symfony\Component\Validator\Constraints as Assert;

class WalletCreateCommand
{
    /**
     * @var float
     * @Assert\NotBlank(message="Campo obrigatório")
     */
    private $balance;

    /**
     * @var string|null
     */
    private $message;

    /**
     * CreateUserCommand constructor.
     * @param string $balance
     */
    public function __construct(
        string $balance
    ) {
        $this->balance = $balance;
    }

    /**
     * @return float
     */
    public function getBalance(): float
    {
        return $this->balance;
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