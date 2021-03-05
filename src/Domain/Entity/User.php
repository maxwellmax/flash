<?php

namespace App\Domain\Entity;

use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * User
 *
 * @ORM\Table(name="User", uniqueConstraints={@ORM\UniqueConstraint(name="User_cpf_cnpj_uindex", columns={"cpf_cnpj"}),
 *      @ORM\UniqueConstraint(name="User_email_uindex", columns={"email"})},
 *      indexes={@ORM\Index(name="User_UserType_uuid_fk", columns={"id_user_type"}),
 *      @ORM\Index(name="User_Wallet_id_fk", columns={"id_wallet"})})
 * @ORM\Entity(repositoryClass=App\Domain\Infrastructure\Doctrine\Repository\UserRepository::class)
 */
class User implements UserInterface
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false, options={"unsigned"=true,"comment"="The id of user"})
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="uuid", type="string", length=36, nullable=false, options={"comment"="Universal Unique Id used for events"})
     */
    private $uuid;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, nullable=false)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=255, nullable=false, unique=true, options={"comment"="User E-mail - Must be unique"})
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="password", type="string", length=255, nullable=false, options={"comment"="User password"})
     */
    private $password;

    /**
     * @var string
     *
     * @ORM\Column(name="cpf_cnpj", type="string", length=16, nullable=false, unique=true, options={"comment"="User CPF/CNPJ - Must be unique"})
     */
    private $cpfCnpj;

    /**
     * @var UserType
     *
     * @ORM\ManyToOne(targetEntity="UserType")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_user_type", referencedColumnName="id")
     * })
     */
    private $userType;

    /**
     * @var Wallet
     *
     * @ORM\ManyToOne(targetEntity="Wallet")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_wallet", referencedColumnName="id")
     * })
     */
    private $wallet;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime", nullable=false, options={"default"="CURRENT_TIMESTAMP","comment"="Datetime of insert"})
     */
    private $createdAt = 'CURRENT_TIMESTAMP';

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updated_at", type="datetime", nullable=false, options={"default"="CURRENT_TIMESTAMP","comment"="Datetime of update"})
     */
    private $updatedAt = 'CURRENT_TIMESTAMP';

    /**
     * User constructor.
     * @param string $uuid
     * @param string $name
     * @param string $email
     * @param string $password
     * @param string $cpfCnpj
     * @param UserType $userType
     * @param Wallet $wallet
     * @throws \Exception
     */
    public function __construct(string $uuid, string $name, string $email, string $password, string $cpfCnpj, UserType $userType, Wallet $wallet)
    {
        $this->uuid = $uuid;
        $this->name = $name;
        $this->email = $email;
        $this->password = $password;
        $this->cpfCnpj = $cpfCnpj;
        $this->userType = $userType;
        $this->wallet = $wallet;
        $this->createdAt = new \Datetime();
        $this->updatedAt = new \Datetime();
    }

    /**
     * @param string $name
     * @param string $email
     * @throws \Exception
     */
    public function put(
        string $name,
        string $email
    ) {
        $this->name = $name;
        $this->email = $email;
        $this->updatedAt = new \Datetime();
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return UuidInterface
     */
    public function getUuid(): UuidInterface
    {
        return Uuid::getFactory()->fromString($this->uuid);
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string|null
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    public function getUsername(): string
    {
        return $this->email;
    }

    /**
     * @return string
     */
    public function getCpfCnpj(): string
    {
        return $this->cpfCnpj;
    }

    /**
     * @return UserType
     */
    public function getUserType(): UserType
    {
        return $this->userType;
    }

    /**
     * @return Wallet
     */
    public function getWallet(): Wallet
    {
        return $this->wallet;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }

    /**
     * @return \DateTime
     */
    public function getUpdatedAt(): \DateTime
    {
        return $this->updatedAt;
    }

    /**
     * @param string $password
     */
    public function changePassword(string $password)
    {
        $this->password = $password;
    }


    public function getRoles()
    {
        return array('ROLE_USER');
    }


    public function getSalt()
    {
    }


    public function eraseCredentials()
    {
    }
}
