<?php


namespace MisterIcy\RnR\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class Status
 * @package MisterIcy\RnR\Entity
 * @ORM\Entity
 * @ORM\Table(name="stata")
 */
class Status
{
    /**
     * Status Identifier.
     *
     * @ORM\Id
     * @ORM\Column(type="integer", name="id")
     * @ORM\GeneratedValue(strategy="AUTO")
     *
     * @var int
     */
    private int $id;

    /**
     * Name
     *
     * @ORM\Column(type="string", length=64, name="name")
     *
     * @var string
     */
    private string $name;

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return Status
     */
    public function setName(string $name): Status
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

}
