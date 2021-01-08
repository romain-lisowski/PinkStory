<?php

declare(strict_types=1);

namespace App\Test\User\Command;

use App\Exception\ValidatorException;
use App\User\Command\UserRemoveProfilePictureCommand;
use App\User\Command\UserRemoveProfilePictureCommandHandler;
use App\User\Entity\User;
use App\User\Message\UserRemoveProfilePictureMessage;
use App\User\Repository\UserRepositoryInterface;
use App\User\Upload\UserProfilePictureUploaderInterface;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NoResultException;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Prophecy\Prophet;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\Exception\FileNotFoundException;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * @internal
 * @coversNothing
 */
final class UserRemoveProfilePictureCommandHandlerTest extends TestCase
{
    private Prophet $prophet;
    private UserRemoveProfilePictureCommand $command;
    private UserRemoveProfilePictureCommandHandler $handler;
    private User $user;
    private $entityManager;
    private $bus;
    private $validator;
    private $userProfilePictureUploader;
    private $userRepository;

    public function setUp(): void
    {
        $this->prophet = new Prophet();

        $this->user = (new User())
            ->rename('Yannis')
            ->updateEmail('auth@yannissgarra.com')
            ->setProfilePictureDefined(true)
        ;

        $filesystem = new Filesystem();
        $filesystem->touch(sys_get_temp_dir().'/'.$this->user->getId().'.jpg');

        $this->command = new UserRemoveProfilePictureCommand();
        $this->command->id = $this->user->getId();

        $this->entityManager = $this->prophet->prophesize(EntityManagerInterface::class);

        $this->bus = $this->prophet->prophesize(MessageBusInterface::class);

        $this->validator = $this->prophet->prophesize(ValidatorInterface::class);

        $this->userProfilePictureUploader = $this->prophet->prophesize(UserProfilePictureUploaderInterface::class);

        $this->userRepository = $this->prophet->prophesize(UserRepositoryInterface::class);

        $this->handler = new UserRemoveProfilePictureCommandHandler($this->entityManager->reveal(), $this->bus->reveal(), $this->validator->reveal(), $this->userProfilePictureUploader->reveal(), $this->userRepository->reveal());
    }

    public function tearDown(): void
    {
        $filesystem = new Filesystem();
        $filesystem->remove([
            sys_get_temp_dir().'/'.$this->user->getId().'.jpg',
        ]);

        $this->prophet->checkPredictions();
    }

    public function testHandleSucess(): void
    {
        $lastUpdatedAt = $this->user->getLastUpdatedAt();

        $this->validator->validate($this->command)->shouldBeCalledOnce()->willReturn(new ConstraintViolationList());

        $this->userRepository->findOne($this->command->id)->shouldBeCalledOnce()->willReturn($this->user);

        $this->entityManager->flush()->shouldBeCalledOnce();

        $this->userProfilePictureUploader->setUser(Argument::type(User::class))->shouldBeCalledOnce();
        $this->userProfilePictureUploader->remove()->shouldBeCalledOnce();

        $this->bus->dispatch(Argument::type(UserRemoveProfilePictureMessage::class))->shouldBeCalledOnce()->willReturn(new Envelope(new UserRemoveProfilePictureMessage($this->user->getId())));

        $this->handler->handle($this->command);

        $this->assertFalse($this->user->hasProfilePicture());
        $this->assertNotEquals($this->user->getLastUpdatedAt(), $lastUpdatedAt);
    }

    public function testHandleFailInvalidCommand(): void
    {
        $this->validator->validate($this->command)->shouldBeCalledOnce()->willReturn(new ConstraintViolationList([new ConstraintViolation('error', null, [], false, 'field', null, null, null, null)]));

        $this->userRepository->findOne($this->command->id)->shouldNotBeCalled();

        $this->entityManager->flush()->shouldNotBeCalled();

        $this->userProfilePictureUploader->setUser(Argument::type(User::class))->shouldNotBeCalled();
        $this->userProfilePictureUploader->remove()->shouldNotBeCalled();

        $this->bus->dispatch(Argument::type(UserRemoveProfilePictureMessage::class))->shouldNotBeCalled();

        $this->expectException(ValidatorException::class);

        $this->handler->handle($this->command);
    }

    public function testHandleFailUserNotFound(): void
    {
        $this->validator->validate($this->command)->shouldBeCalledOnce()->willReturn(new ConstraintViolationList());

        $this->userRepository->findOne($this->command->id)->shouldBeCalledOnce()->willThrow(new NoResultException());

        $this->entityManager->flush()->shouldNotBeCalled();

        $this->userProfilePictureUploader->setUser(Argument::type(User::class))->shouldNotBeCalled();
        $this->userProfilePictureUploader->remove()->shouldNotBeCalled();

        $this->bus->dispatch(Argument::type(UserRemoveProfilePictureMessage::class))->shouldNotBeCalled();

        $this->expectException(NoResultException::class);

        $this->handler->handle($this->command);
    }

    public function testHandleSuccessNoProfilePicture(): void
    {
        $this->user->setProfilePictureDefined(false);

        $this->validator->validate($this->command)->shouldBeCalledOnce()->willReturn(new ConstraintViolationList());

        $this->userRepository->findOne($this->command->id)->shouldBeCalledOnce()->willReturn($this->user);

        $this->entityManager->flush()->shouldNotBeCalled();

        $this->userProfilePictureUploader->setUser(Argument::type(User::class))->shouldNotBeCalled();
        $this->userProfilePictureUploader->remove()->shouldNotBeCalled();

        $this->bus->dispatch(Argument::type(UserRemoveProfilePictureMessage::class))->shouldNotBeCalled();

        $this->handler->handle($this->command);

        $this->expectNotToPerformAssertions();
    }

    public function testHandleFailProfilePictureNotFound(): void
    {
        $this->validator->validate($this->command)->shouldBeCalledOnce()->willReturn(new ConstraintViolationList());

        $this->userRepository->findOne($this->command->id)->shouldBeCalledOnce()->willReturn($this->user);

        $this->entityManager->flush()->shouldBeCalledOnce();

        $this->userProfilePictureUploader->setUser(Argument::type(User::class))->shouldBeCalledOnce();
        $this->userProfilePictureUploader->remove()->shouldBeCalledOnce()->willThrow(new FileNotFoundException($this->user->getProfilePicturePath()));

        $this->bus->dispatch(Argument::type(UserRemoveProfilePictureMessage::class))->shouldNotBeCalled();

        $this->expectException(FileNotFoundException::class);

        $this->handler->handle($this->command);
    }
}
