<?php

namespace NotificationBundle\Entity;

use AppBundle\Entity\Part\TimeTrackableTrait;
use AppBundle\Entity\User;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * Notification
 *
 * @ORM\Table(name="notification")
 * @ORM\Entity(repositoryClass="NotificationBundle\Repository\NotificationRepository")
 * @ORM\HasLifecycleCallbacks
 */
class Notification
{
    use TimeTrackableTrait;

    const READ_STATUS_NEW = 'NEW';
    const READ_STATUS_VIEWED = 'VIEWED';

    const TYPE_NOTIFICATION = 'NOTIFICATION';
    const TYPE_IMPORTANT = 'IMPORTANT';
    const TYPE_NEWS = 'NEWS';
    const TYPE_DEFAULT = self::TYPE_NOTIFICATION;

    /**
     * Notification constructor.
     *
     * @param User   $user
     * @param string $message
     * @param string $type
     */
    public function __construct(User $user, string $message, string $type = self::TYPE_DEFAULT)
    {
        $this->user = $user;
        $this->message = $message;
        $this->type = $type;
    }

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @NotBlank
     *
     * @var UserNotificationInterface
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User", inversedBy="notifications")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $user;

    /**
     * @NotBlank
     *
     * @var string
     *
     * @ORM\Column(name="message", type="string", length=255)
     */
    private $message;

    /**
     * @var string
     *
     * @ORM\Column(name="read_status", type="string", length=10)
     */
    private $readStatus = self::READ_STATUS_NEW;

    /**
     * @var string
     *
     * @ORM\Column(name="type", type="string", length=50)
     */
    private $type;


    /**
     * Get id.
     *
     * @return int
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Set user.
     *
     * @param UserNotificationInterface $user
     *
     * @return Notification
     */
    public function setUser(UserNotificationInterface $user): self
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user.
     *
     * @return UserNotificationInterface
     */
    public function getUser(): UserNotificationInterface
    {
        return $this->user;
    }

    /**
     * Set message.
     *
     * @param string $message
     *
     * @return Notification
     */
    public function setMessage(string $message): self
    {
        $this->message = $message;

        return $this;
    }

    /**
     * Get message.
     *
     * @return string
     */
    public function getMessage(): string
    {
        return $this->message;
    }

    /**
     * Set readStatus.
     *
     * @param string $readStatus
     *
     * @return Notification
     */
    public function setReadStatus(string $readStatus): self
    {
        $this->readStatus = $readStatus;

        return $this;
    }

    /**
     * Get readStatus.
     *
     * @return string
     */
    public function getReadStatus(): string
    {
        return $this->readStatus;
    }

    /**
     * Set type.
     *
     * @param string $type
     *
     * @return Notification
     */
    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type.
     *
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }
}
