<?php
namespace App\Domain\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;

/**
 * Transfer
 *
 * @ORM\Table(name="Transfer", indexes={@ORM\Index(name="Transfer_TransferStatus_uuid_fk", columns={"id_transfer_status"}),
 *      @ORM\Index(name="Transfer_User_id_fk", columns={"payer"}),
 *      @ORM\Index(name="Transfer_TransferType_uuid_fk", columns={"id_type_transfer"}),
 *      @ORM\Index(name="Transfer_User_id_fk_2", columns={"payee"})})
 * @ORM\Entity
 */
class Transfer
{
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
     * @ORM\Column(name="uuid", type="string", length=36, nullable=false, options={"fixed"=true})
     */
    private $uuid;

    /**
     * @var string
     *
     * @ORM\Column(name="value", type="decimal", precision=5, scale=2, nullable=false)
     */
    private $value;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime", nullable=false, options={"default"="CURRENT_TIMESTAMP"})
     */
    private $createdAt = 'CURRENT_TIMESTAMP';

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updated_at", type="datetime", nullable=false, options={"default"="CURRENT_TIMESTAMP"})
     */
    private $updatedAt = 'CURRENT_TIMESTAMP';

    /**
     * @var Transferstatus
     *
     * @ORM\ManyToOne(targetEntity="Transferstatus")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_transfer_status", referencedColumnName="id")
     * })
     */
    private $transferStatus;

    /**
     * @var Transfertype
     *
     * @ORM\ManyToOne(targetEntity="Transfertype")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_type_transfer", referencedColumnName="id")
     * })
     */
    private $transferType;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="payer", referencedColumnName="id")
     * })
     */
    private $payer;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="payee", referencedColumnName="id")
     * })
     */
    private $payee;

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
    public function getUuid(): string
    {
        return $this->uuid;
    }

    /**
     * @return string
     */
    public function getValue(): string
    {
        return $this->value;
    }

    /**
     * @return DateTime
     */
    public function getCreatedAt(): DateTime
    {
        return $this->createdAt;
    }

    /**
     * @return DateTime
     */
    public function getUpdatedAt(): DateTime
    {
        return $this->updatedAt;
    }

    /**
     * @return Transferstatus
     */
    public function getTransferStatus(): Transferstatus
    {
        return $this->transferStatus;
    }

    /**
     * @return Transfertype
     */
    public function getTransferType(): Transfertype
    {
        return $this->transferType;
    }

    /**
     * @return User
     */
    public function getPayer(): User
    {
        return $this->payer;
    }

    /**
     * @return User
     */
    public function getPayee(): User
    {
        return $this->payee;
    }


}
