<?php
namespace App\Domain\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Transfertype
 *
 * @ORM\Table(name="TransferType")
 * @ORM\Entity
 */
class Transfertype
{
    const TRANSFER_TYPE_TRANFERENCIA = 1;
    const TRANSFER_TYPE_DINHEIRO = 2;

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string|null
     *
     * @ORM\Column(name="description", type="string", length=100, nullable=true)
     */
    private $description;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string|null
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }


}
