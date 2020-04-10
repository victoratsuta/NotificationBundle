<?php

namespace Tests\Functional\Channels;

use NotificationBundle\ChannelModels\Sms;
use NotificationBundle\Channels\SmsChannel;
use NotificationBundle\Clients\SmsRuClient;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class SmsChannelTest extends KernelTestCase
{
    private $client;

    protected function setUp()
    {
        $this->client = self::bootKernel();
    }

    public function testSuccessSend()
    {
        $mock = $this->getMockBuilder(SmsRuClient::class)
            ->disableOriginalConstructor()
            ->getMock();

        $mock->expects($this->once())
            ->method('sendSMS');

        $this->client->getContainer()->set(SmsRuClient::class, $mock);

        $model = new Sms();
        $model->setPhone('+79787151111');

        $model->setBody('Test');
        $model->setFrom('testsender');

        $chanel = $this->client->getContainer()->get(SmsChannel::class);
        $chanel->send($model);

    }

}