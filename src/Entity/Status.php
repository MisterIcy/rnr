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
     * @ORM\Id
     * @ORM\Column(type="string", length=64)
     *
     * @var string
     */
    private string $type;

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
     * @return Status
     */
    public function setType(string $type): Status
    {
        $this->type = $type;

        return $this;
    }
}
