<?php

namespace NotificationBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * User
 *
 * @ORM\Table(name="user")
 * @ORM\Entity(repositoryClass="NotificationBundle\Repository\UserRepository")
 */
class User implements UserNotificationInterface, UserWithTelegramInterface
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var ArrayCollection
     * @ORM\OneToMany(targetEntity="Notification", mappedBy="user")
     */
    private $notifications;

    /**
     * @var string
     *
     * @ORM\Column(name="telegram_chat_id", type="string", length=255, nullable=true)
     */
    private $telegramChatId;

    /**
     * @var string
     *
     * @ORM\Column(name="telegram_auth_token", type="string", length=255, nullable=true)
     */
    private $telegramAuthToken;

    /**
     * User constructor.
     */
    public function __construct()
    {
        $this->notifications = new ArrayCollection();
    }

    /**
     * Get id.
     *
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getNotifications()
    {
        return $this->notifications;
    }

    /**
     * @param ArrayCollection $notifications
     */
    public function setNotifications(ArrayCollection $notifications): void
    {
        $this->notifications = $notifications;
    }

    /**
     * @return string
     */
    public function getTelegramChatId(): string
    {
        return $this->telegramChatId;
    }

    /**
     * @param string $telegramChatId
     */
    public function setTelegramChatId(string $telegramChatId): void
    {
        $this->telegramChatId = $telegramChatId;
    }

    /**
     * @return string
     */
    public function getTelegramAuthToken(): string
    {
        return $this->telegramAuthToken;
    }

    /**
     * @param string $telegramAuthToken
     */
    public function setTelegramAuthToken(string $telegramAuthToken): void
    {
        $this->telegramAuthToken = $telegramAuthToken;
    }
}
