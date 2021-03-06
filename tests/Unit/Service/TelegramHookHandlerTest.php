<?php

namespace NotificationBundle\tests\Unit\Service;

use AppBundle\Entity\User;
use Doctrine\Common\Persistence\ObjectRepository;
use Doctrine\ORM\EntityManagerInterface;
use NotificationBundle\Exception\NoUserWithTelegramTokenException;
use NotificationBundle\Exception\ValidationTelegramHookException;
use NotificationBundle\Service\TelegramHookHandler;
use ReflectionException;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Validator\Validation;

class TelegramHookHandlerTest extends KernelTestCase
{
    /**
     * @throws NoUserWithTelegramTokenException
     * @throws ValidationTelegramHookException
     * @throws ReflectionException
     */
    public function testTelegramHookHandlerValidationError()
    {
        $this->expectException(ValidationTelegramHookException::class);

        $validator = Validation::createValidator();
        $objectManager = $this->createMock(EntityManagerInterface::class);

        $handler = new TelegramHookHandler($validator, $objectManager);

        $handler->handle([]);
    }

    /**
     * @throws NoUserWithTelegramTokenException
     * @throws ValidationTelegramHookException
     * @throws ReflectionException
     */
    public function testTelegramHookHandlerNoUserError()
    {
        $this->expectException(NoUserWithTelegramTokenException::class);

        $validator = Validation::createValidator();
        $mock = $this->createMock(EntityManagerInterface::class);

        $repository = $this->createMock(ObjectRepository::class);
        $repository->expects($this->any())
            ->method('findOneBy')
            ->willReturn(null);

        $mock->expects($this->any())
            ->method('getRepository')
            ->willReturn($repository);

        $handler = new TelegramHookHandler($validator, $mock);

        $handler->handle([
            'message' => [
                'chat' => [
                    'id' => '13r445hg356'
                ],
                'text' => '/start ' . 'token'
            ]
        ]);
    }

    /**
     * @throws NoUserWithTelegramTokenException
     * @throws ValidationTelegramHookException
     * @throws ReflectionException
     */
    public function testTelegramHookHandler()
    {
        $validator = Validation::createValidator();

        $user = new User();
        $user->setTelegramAuthToken('token');

        $repository = $this->createMock(ObjectRepository::class);
        $repository->expects($this->any())
            ->method('findOneBy')
            ->willReturn($user);

        $objectManager = $this->createMock(EntityManagerInterface::class);
        $objectManager->expects($this->any())
            ->method('getRepository')
            ->willReturn($repository);

        $handler = new TelegramHookHandler($validator, $objectManager);

        $chatId = '13r445hg356';
        $handler->handle([
            'message' => [
                'chat' => [
                    'id' => '13r445hg356'
                ],
                'text' => '/start ' . 'token'
            ]
        ]);

        $this->assertEquals($user->getTelegramChatId(), $chatId);
    }
}