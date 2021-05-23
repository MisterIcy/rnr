<?php


namespace MisterIcy\RnR\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;
use JMS\Serializer\Annotation\Exclude;
use MisterIcy\RnR\DateTimeHelper;

/**
 * Class Leave
 * @package MisterIcy\RnR\Entity
 *
 * @ORM\Entity
 * @ORM\Table(name="leaves")
 */
class Leave
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     *
     * @var int
     */
    private int $id;

    /**
     * Requester.
     *
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     *
     * @var User|null
     */
    private ?User $requester;

    /**
     * Approver.
     *
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(name="approver", referencedColumnName="id", nullable=true)
     *
     * @Exclude
     *
     * @var User|null
     */
    private ?User $approver;

    /**
     * Status
     *
     * @ORM\ManyToOne(targetEntity="Status")
     * @ORM\JoinColumn(name="status", referencedColumnName="id")
     *
     * @var \MisterIcy\RnR\Entity\Status|null
     */
    private ?Status $status;

    /**
     * Start Date
     *
     * @ORM\Column(type="date", name="date_start")
     * @Serializer\Type("DateTime<'d-m-Y'>")
     *
     * @var \DateTime
     */
    private DateTime $startDate;

    /**
     * End Date
     *
     * @ORM\Column(type="date", name="date_end")
     *
     * @Serializer\Type("DateTime<'d-m-Y'>")
     *
     * @var \DateTime
     */
    private DateTime $endDate;

    /**
     * Creation Date
     *
     * @ORM\Column(type="datetime", name="date_created", nullable=true)
     *
     * @Serializer\Type("DateTime<'d-m-Y'>")
     *
     * @var \DateTime|null
     */
    private $createdDate;

    /**
     * Modification Date
     *
     * @ORM\Column(type="datetime", name="date_updated", nullable=true)
     *
     * @var \DateTime|null
     */
    private $modifiedDate;

    /**
     * Reason
     *
     * @ORM\Column(type="text", nullable=true)
     *
     * @var string|null
     */
    private ?string $reason;

    public function __construct() {

    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return \MisterIcy\RnR\Entity\User|null
     */
    public function getRequester(): ?User
    {
        return $this->requester;
    }

    /**
     * @param \MisterIcy\RnR\Entity\User|null $requester
     * @return Leave
     */
    public function setRequester(?User $requester): Leave
    {
        $this->requester = $requester;

        return $this;
    }

    /**
     * @return \MisterIcy\RnR\Entity\User|null
     */
    public function getApprover(): ?User
    {
        return $this->approver;
    }

    /**
     * @param \MisterIcy\RnR\Entity\User|null $approver
     * @return Leave
     */
    public function setApprover(?User $approver): Leave
    {
        $this->approver = $approver;

        return $this;
    }

    /**
     * @return \MisterIcy\RnR\Entity\Status|null
     */
    public function getStatus(): ?Status
    {
        return $this->status;
    }

    /**
     * @param \MisterIcy\RnR\Entity\Status|null $status
     * @return Leave
     */
    public function setStatus(?Status $status): Leave
    {
        $this->status = $status;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getStartDate(): DateTime
    {
        return $this->startDate;
    }

    /**
     * @param \DateTime $startDate
     * @return Leave
     */
    public function setStartDate(DateTime $startDate): Leave
    {
        $this->startDate = $startDate;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getEndDate(): DateTime
    {
        return $this->endDate;
    }

    /**
     * @param \DateTime $endDate
     * @return Leave
     */
    public function setEndDate(DateTime $endDate): Leave
    {
        $this->endDate = $endDate;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedDate(): DateTime
    {
        return $this->createdDate;
    }

    /**
     * @param \DateTime $createdDate
     * @return Leave
     */
    public function setCreatedDate(DateTime $createdDate): Leave
    {
        $this->createdDate = $createdDate;

        return $this;
    }

    /**
     * @return \DateTime|null
     */
    public function getModifiedDate(): ?DateTime
    {
        return $this->modifiedDate;
    }

    /**
     * @param \DateTime|null $modifiedDate
     * @return Leave
     */
    public function setModifiedDate(?DateTime $modifiedDate): Leave
    {
        $this->modifiedDate = $modifiedDate;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getReason(): ?string
    {
        return $this->reason;
    }

    /**
     * @param string|null $reason
     * @return Leave
     */
    public function setReason(?string $reason): Leave
    {
        $this->reason = $reason;

        return $this;
    }
    /**
     * @Serializer\VirtualProperty()
     */
    public function getDays() : int
    {
        if (!empty($this->startDate) && !empty($this->endDate)) {
            return DateTimeHelper::calculateBusinessDays(clone $this->startDate, clone $this->endDate);
        }
        return 0;
    }





}
