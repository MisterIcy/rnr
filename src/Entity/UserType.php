<?php


namespace MisterIcy\RnR\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class UserType
 * @package MisterIcy\RnR\Entity
 * @ORM\Entity
 * @ORM\Table(name="user_types")
 */
class UserType
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     * @var int
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=32, nullable=false)
     *
     * @var string
     */
    private string $type;

    /**
     * @ORM\Column(type="boolean", name="is_admin")
     *
     * @var bool
     */
    private bool $isAdmin = false;

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
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @param string $type
     * @return UserType
     */
    public function setType(string $type): UserType
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return bool
     */
    public function isAdmin(): bool
    {
        return $this->isAdmin;
    }

    /**
     * @param bool $isAdmin
     * @return UserType
     */
    public function setIsAdmin(bool $isAdmin): UserType
    {
        $this->isAdmin = $isAdmin;

        return $this;
    }
}
