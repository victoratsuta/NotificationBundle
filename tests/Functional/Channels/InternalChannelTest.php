<?php

namespace Tests\Functionsl\Channels;

use Doctrine\ORM\EntityManager;
use NotificationBundle\Channels\InternalChannel;
use NotificationBundle\Entity\Notification;
use NotificationBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\HttpKernel\KernelInterface;

class InternalChannelTest extends KernelTestCase
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
        $this->client = self::bootKernel();
        $this->em = $this->client->getContainer()->get('doctrine.orm.entity_manager');
    }

    public function testSuccessSend(){

        $entityManager = $this->client->getContainer()->get('doctrine.orm.entity_manager');

        $user = new User;
        $entityManager->persist($user);

        $model = new Notification();

        $randomString = sha1(rand()) ;
        $model->setMessage($randomString);
        $model->setUser($user);

        $chanel =  $this->client->getContainer()->get(InternalChannel::class);

        $chanel->send($model);

        $item = $this->em
            ->getRepository(Notification::class)
            ->findOneBy(['message' => $randomString]);

        $this->assertInstanceOf(Notification::class, $item);

    }
}