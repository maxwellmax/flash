<?php
namespace App\Domain\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Transferstatus
 *
 * @ORM\Table(name="TransferStatus")
 * @ORM\Entity
 */
class Transferstatus
{
    const TRANSFER_STATUS_AUTORIZADO = 1;
    const TRANSFER_STATUS_ENVIADO = 2;
    const TRANSFER_STATUS_FINALIZADO = 3;
    const TRANSFER_STATUS_ESTORNADO = 4;

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="status", type="string", length=30, nullable=false)
     */
    private $status;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getStatus(): string
    {
        return $this->status;
    }


}
