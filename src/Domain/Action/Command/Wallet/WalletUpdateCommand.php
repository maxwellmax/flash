<?php

namespace App\Domain\Action\Command\Wallet;

use Symfony\Component\Validator\Constraints as Assert;

class WalletUpdateCommand
{
    /**
     * @var int
     */
    private $id;

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
     * WalletUpdateCommand constructor.
     * @param int $id
     * @param string $balance
     */
    public function __construct(
        int $id,
        string $balance
    ) {
        $this->id = $id;
        $this->balance = $balance;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
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