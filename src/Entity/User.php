<?php

declare(strict_types=1);

namespace MisterIcy\RnR\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class User
 * @package MisterIcy\RnR\Entity
 * @ORM\Entity()
 * @ORM\Table(name="users")
 */
class User
{
    /**
     * User Identifier.
     *
     * @ORM\Id
     * @ORM\Column(type="integer", name="id")
     * @ORM\GeneratedValue(strategy="AUTO")
     *
     * @var int
     */
    private int $id;

    /**
     * Given Name.
     *
     * @ORM\Column(type="string", length=64, name="given_name")
     *
     * @var string
     */
    private string $givenName;

    /**
     * Family Name
     *
     * @ORM\Column(type="string", length=64, name="family_name")
     *
     * @var string
     */
    private string $familyName;

    /**
     * Email.
     *
     * @ORM\Column(type="string", unique=true, name="email", length=120)
     *
     * @var string
     */
    private string $email;

    /**
     * Encrypted Password
     *
     * @ORM\Column(type="string", length=120)
     *
     * @var string
     */
    private string $password;

    /**
     * @ORM\ManyToOne(targetEntity="UserType")
     * @ORM\JoinColumn(name="user_type", referencedColumnName="id", nullable=true)
     *
     * @var \MisterIcy\RnR\Entity\UserType|null
     */
    private ?UserType $userType;

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
    public function getGivenName(): string
    {
        return $this->givenName;
    }

    /**
     * @param string $givenName
     * @return User
     */
    public function setGivenName(string $givenName): User
    {
        $this->givenName = $givenName;

        return $this;
    }

    /**
     * @return string
     */
    public function getFamilyName(): string
    {
        return $this->familyName;
    }

    /**
     * @param string $familyName
     * @return User
     */
    public function setFamilyName(string $familyName): User
    {
        $this->familyName = $familyName;

        return $this;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @param string $email
     * @return User
     */
    public function setEmail(string $email): User
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @param string $password
     * @return User
     */
    public function setPassword(string $password): User
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @return \MisterIcy\RnR\Entity\UserType|null
     */
    public function getUserType(): ?UserType
    {
        return $this->userType;
    }

    /**
     * @param \MisterIcy\RnR\Entity\UserType|null $userType
     * @return User
     */
    public function setUserType(?UserType $userType): User
    {
        $this->userType = $userType;

        return $this;
    }
}
