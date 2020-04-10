<?php

namespace Tests\Functionsl\Channels;

use NotificationBundle\ChannelModels\Email;
use NotificationBundle\Channels\EmailChannel;
use NotificationBundle\Clients\EsputnikClient;
use NotificationBundle\Clients\SmsRuClient;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class EmailChannelTest extends KernelTestCase
{

    private $client;

    protected function setUp()
    {
        $this->client = self::bootKernel();
    }

    public function testSuccessSend()
    {

        $mock = $this->getMockBuilder(EsputnikClient::class)
            ->disableOriginalConstructor()
            ->getMock();

        $mock->expects($this->once())
            ->method('sendEmail');

        $this->client->getContainer()->set(EsputnikClient::class, $mock);

        $model = new Email();

        $model->setTemplateId('2031908');
        $model->setToEmail('atsutavictor.dev@gmail.com');
        $model->setParams(
            [
                "name" => "Test"
            ]
        );

        $chanel = $this->client->getContainer()->get(EmailChannel::class);

        $chanel->send($model);

        $this->assertTrue(true);

    }
}