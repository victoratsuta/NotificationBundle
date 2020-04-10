<?php

namespace NotificationBundle\tests\Functional\Channels;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use NotificationBundle\ChannelModels\Telegram;
use NotificationBundle\Channels\TelegramChannel;
use NotificationBundle\Clients\TelegramClient;
use NotificationBundle\Entity\User;
use NotificationBundle\Event\ConfigureTelegramEvent;
use NotificationBundle\EventSubscriber\ConfigureTelegramEventSubscriber;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpKernel\KernelInterface;

class TelegramChannelTest extends WebTestCase
{

    // TODO configure test DB after integration with main APP

    /**
     * @var  KernelInterface
     */
    private $client;

    /**
     * @var  EntityManager
     */
    private $em;

    protected function setUp()
    {
        $this->client = self::createClient();
        $this->em = $this->client->getContainer()->get('doctrine.orm.entity_manager');
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function testIntegration()
    {

        /////////////  dispatch event, check if user have some auth telegram token

        $user = new User;
        $this->em->persist($user);
        $this->em->flush();

        $event = new ConfigureTelegramEvent($user);
        $subscriber = new ConfigureTelegramEventSubscriber($this->em);
        $subscriber->setTelegramAuthToken($event);

        $this->em->flush();

        $user = $this->em
            ->getRepository(User::class)
            ->find($user->getId());

        $token = $user->getTelegramAuthToken();

        $this->assertTrue(is_string($token));

        ///////////// fire api with token, and set chat id to user

        $this->client->request('POST', '/notifiactions/telegram/hook', [
            'message' => [
                'chat' => [
                    'id' => '13r445hg356'
                ],
                'text' => '/start ' . $token
            ]
        ]);

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());

        $user = $this->em
            ->getRepository(User::class)
            ->find($user->getId());

        $chatId = $user->getTelegramChatId();

        $this->assertTrue(is_string($chatId));

        ///////////// fire telegram chanel, send some message

        $mock = $this->getMockBuilder(TelegramClient::class)
            ->disableOriginalConstructor()
            ->getMock();

        $mock->expects($this->once())
            ->method('sendTelegram');

        $this->client->getContainer()->set(TelegramClient::class, $mock);

        $model = new Telegram();
        $model->setChatId($chatId);
        $model->setMessage('some notification message');

        $chanel = $this->client->getContainer()->get(TelegramChannel::class);

        $chanel->send($model);

    }

}