<?php

declare(strict_types=1);

namespace App\Test\User\Command;

use App\User\Command\UserValidateEmailCommand;
use App\User\Command\UserValidateEmailCommandHandler;
use App\User\Entity\User;
use App\User\Repository\UserRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NoResultException;
use PHPUnit\Framework\TestCase;
use Prophecy\Prophet;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Uid\Uuid;

/**
 * @internal
 * @coversNothing
 */
final class UserValidateEmailCommandHandlerTest extends TestCase
{
    private Prophet $prophet;
    private UserValidateEmailCommand $command;
    private UserValidateEmailCommandHandler $handler;
    private User $user;
    private $entityManager;
    private $userRepository;

    public function setUp(): void
    {
        $this->prophet = new Prophet();

        $this->user = (new User())
            ->rename('Yannis')
            ->updateEmail('auth@yannissgarra.com')
            ->regenerateEmailValidationSecret()
        ;

        $this->command = new UserValidateEmailCommand();
        $this->command->id = $this->user->getId();
        $this->command->secret = $this->user->getEmailValidationSecret();

        $this->entityManager = $this->prophet->prophesize(EntityManagerInterface::class);

        $this->userRepository = $this->prophet->prophesize(UserRepositoryInterface::class);

        $this->handler = new UserValidateEmailCommandHandler($this->entityManager->reveal(), $this->userRepository->reveal());
    }

    public function tearDown(): void
    {
        $this->prophet->checkPredictions();
    }

    public function testHandleSucess(): void
    {
        $lastUpdatedAt = $this->user->getLastUpdatedAt();

        $this->userRepository->findOneByActiveEmailValidationSecret($this->command->secret)->shouldBeCalledOnce()->willReturn($this->user);

        $this->entityManager->flush()->shouldBeCalledOnce();

        $this->handler->handle($this->command);

        $this->assertTrue($this->user->isEmailValidated());
        $this->assertTrue($this->user->isEmailValidationSecretUsed());
        $this->assertNotEquals($this->user->getLastUpdatedAt(), $lastUpdatedAt);
    }

    public function testHandleFailWrongSecret(): void
    {
        $this->userRepository->findOneByActiveEmailValidationSecret($this->command->secret)->shouldBeCalledOnce()->willThrow(new NoResultException());

        $this->entityManager->flush()->shouldNotBeCalled();

        $this->expectException(NoResultException::class);

        $this->handler->handle($this->command);
    }

    public function testHandleFailIdAndSecretNotMatch(): void
    {
        $this->command->id = Uuid::v4()->toRfc4122();

        $this->userRepository->findOneByActiveEmailValidationSecret($this->command->secret)->shouldBeCalledOnce()->willReturn($this->user);

        $this->entityManager->flush()->shouldNotBeCalled();

        $this->expectException(AccessDeniedException::class);

        $this->handler->handle($this->command);
    }
}
