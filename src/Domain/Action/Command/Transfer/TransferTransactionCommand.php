<?php

namespace App\Domain\Action\Command\Transfer;

use Ramsey\Uuid\UuidInterface;
use Symfony\Component\Validator\Constraints as Assert;

class TransferTransactionCommand
{
    /**
     * @var UuidInterface
     */
    private $uuid;

    /**
     * @var float
     * @Assert\NotBlank(message="Campo obrigatório")
     */
    private $value;

    /**
     * @var int
     * @Assert\NotBlank(message="Campo obrigatório")
     */
    private $payer;

    /**
     * @var int
     * @Assert\NotBlank(message="Campo obrigatório")
     */
    private $payee;

    /**
     * @var string|null
     */
    private $message;

    /**
     * TransferCreateCommand constructor.
     * @param UuidInterface $uuid
     * @param float $value
     * @param int $payer
     * @param int $payee
     */
    public function __construct(UuidInterface $uuid, float $value, int $payer, int $payee)
    {
        $this->uuid = $uuid;
        $this->value = $value;
        $this->payer = $payer;
        $this->payee = $payee;
    }

    /**
     * @return UuidInterface
     */
    public function getUuid(): UuidInterface
    {
        return $this->uuid;
    }

    /**
     * @return float
     */
    public function getValue(): float
    {
        return $this->value;
    }

    /**
     * @return int
     */
    public function getPayer(): int
    {
        return $this->payer;
    }

    /**
     * @return int
     */
    public function getPayee(): int
    {
        return $this->payee;
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