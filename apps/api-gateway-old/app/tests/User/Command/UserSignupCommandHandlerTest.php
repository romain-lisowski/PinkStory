<?php

declare(strict_types=1);

namespace App\Test\User\Command;

use App\Exception\ValidatorException;
use App\User\Command\UserSignupCommand;
use App\User\Command\UserSignupCommandHandler;
use App\User\Entity\User;
use App\User\Message\UserSignupMessage;
use App\User\Upload\UserProfilePictureUploaderInterface;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Prophecy\Prophet;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * @internal
 * @coversNothing
 */
final class UserSignupCommandHandlerTest extends TestCase
{
    private Prophet $prophet;
    private UserSignupCommand $command;
    private UserSignupCommandHandler $handler;
    private $entityManager;
    private $bus;
    private $passwordEncoder;
    private $validator;
    private $userProfilePictureUploader;

    public function setUp(): void
    {
        $this->prophet = new Prophet();

        $filesystem = new Filesystem();
        $filesystem->touch(sys_get_temp_dir().'/test.jpg');

        $this->command = new UserSignupCommand();
        $this->command->name = 'Yannis';
        $this->command->email = 'auth@yannissgarra.com';
        $this->command->password = '@Password2!';
        $this->command->profilePicture = new UploadedFile(sys_get_temp_dir().'/test.jpg', 'test.jpg', 'image/jpeg', null, true);

        $this->entityManager = $this->prophet->prophesize(EntityManagerInterface::class);

        $this->bus = $this->prophet->prophesize(MessageBusInterface::class);

        $this->passwordEncoder = $this->prophet->prophesize(UserPasswordEncoderInterface::class);

        $this->validator = $this->prophet->prophesize(ValidatorInterface::class);

        $this->userProfilePictureUploader = $this->prophet->prophesize(UserProfilePictureUploaderInterface::class);

        $this->handler = new UserSignupCommandHandler($this->entityManager->reveal(), $this->bus->reveal(), $this->passwordEncoder->reveal(), $this->validator->reveal(), $this->userProfilePictureUploader->reveal());
    }

    public function tearDown(): void
    {
        $filesystem = new Filesystem();
        $filesystem->remove([sys_get_temp_dir().'/test.jpg']);

        $this->prophet->checkPredictions();
    }

    public function testHandleSucess(): void
    {
        $this->validator->validate($this->command)->shouldBeCalledOnce()->willReturn(new ConstraintViolationList());

        $this->passwordEncoder->encodePassword(Argument::type(User::class), $this->command->password)->shouldBeCalledOnce()->willReturn($this->command->password);

        $this->validator->validate(Argument::type(User::class))->shouldBeCalledOnce()->willReturn(new ConstraintViolationList());

        $this->entityManager->persist(Argument::type(User::class))->shouldBeCalledOnce();

        $this->userProfilePictureUploader->setUser(Argument::type(User::class))->shouldBeCalledOnce();
        $this->userProfilePictureUploader->upload($this->command->profilePicture)->shouldBeCalledOnce()->willReturn(true);

        $this->entityManager->flush()->shouldBeCalledOnce();

        $this->bus->dispatch(Argument::type(UserSignupMessage::class))->shouldBeCalledOnce()->willReturn(new Envelope(new UserSignupMessage('uuid')));

        $this->handler->handle($this->command);

        $this->expectNotToPerformAssertions();
    }

    public function testHandleFailInvalidCommand(): void
    {
        $this->validator->validate($this->command)->shouldBeCalledOnce()->willReturn(new ConstraintViolationList([new ConstraintViolation('error', null, [], false, 'field', null, null, null, null)]));

        $this->passwordEncoder->encodePassword(Argument::type(User::class), $this->command->password)->shouldNotBeCalled();

        $this->validator->validate(Argument::type(User::class))->shouldNotBeCalled();

        $this->entityManager->persist(Argument::type(User::class))->shouldNotBeCalled();

        $this->userProfilePictureUploader->setUser(Argument::type(User::class))->shouldNotBeCalled();
        $this->userProfilePictureUploader->upload($this->command->profilePicture)->shouldNotBeCalled();

        $this->entityManager->flush()->shouldNotBeCalled();

        $this->bus->dispatch(Argument::type(UserSignupMessage::class))->shouldNotBeCalled();

        $this->expectException(ValidatorException::class);

        $this->handler->handle($this->command);
    }

    public function testHandleFailInvalidUser(): void
    {
        $this->validator->validate($this->command)->shouldBeCalledOnce()->willReturn(new ConstraintViolationList());

        $this->passwordEncoder->encodePassword(Argument::type(User::class), $this->command->password)->shouldBeCalledOnce()->willReturn($this->command->password);

        $this->validator->validate(Argument::type(User::class))->shouldBeCalledOnce()->willReturn(new ConstraintViolationList([new ConstraintViolation('error', null, [], false, 'field', null, null, null, null)]));

        $this->entityManager->persist(Argument::type(User::class))->shouldNotBeCalled();

        $this->userProfilePictureUploader->setUser(Argument::type(User::class))->shouldNotBeCalled();
        $this->userProfilePictureUploader->upload($this->command->profilePicture)->shouldNotBeCalled();

        $this->entityManager->flush()->shouldNotBeCalled();

        $this->bus->dispatch(Argument::type(UserSignupMessage::class))->shouldNotBeCalled();

        $this->expectException(ValidatorException::class);

        $this->handler->handle($this->command);
    }
}
