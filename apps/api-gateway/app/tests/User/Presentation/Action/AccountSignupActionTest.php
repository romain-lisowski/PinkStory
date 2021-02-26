<?php

declare(strict_types=1);

namespace App\Test\User\Presentation\Action;

use App\Common\Domain\Repository\NoResultException;
use App\Common\Infrastructure\Serializer\Normalizer\DataUriNormalizer;
use App\User\Domain\Event\UserCreatedEvent;
use App\User\Domain\Model\User;
use App\User\Domain\Model\UserGender;
use App\User\Domain\Model\UserRole;
use App\User\Domain\Model\UserStatus;
use App\User\Domain\Security\UserPasswordEncoderInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Uid\Uuid;

/**
 * @internal
 * @coversNothing
 */
final class AccountSignupActionTest extends AbastractUserActionTest
{
    protected static string $httpMethod = Request::METHOD_POST;
    protected static string $httpUri = '/account/signup';

    private static array $userData = [
        'gender' => UserGender::UNDEFINED,
        'name' => 'Test',
        'email' => 'test@pinkstory.io',
        'password' => '@Password2!',
    ];

    protected function setUp(): void
    {
        parent::setUp();

        self::$httpAuthorization = null;
    }

    public function testSuccessWithoutImage(): void
    {
        $this->checkSuccess([
            'gender' => self::$userData['gender'],
            'name' => self::$userData['name'],
            'email' => self::$userData['email'],
            'password' => self::$userData['password'],
        ], [], ['should_have_image_defined' => false]);
    }

    public function testSuccessWithImage(): void
    {
        $this->checkSuccess([
            'gender' => self::$userData['gender'],
            'name' => self::$userData['name'],
            'email' => self::$userData['email'],
            'password' => self::$userData['password'],
            'image' => (new DataUriNormalizer())->normalize(new File(__DIR__.'/../../../image/test.jpg'), ''),
        ], [], ['should_have_image_defined' => true]);
    }

    public function testFailedMissingGender(): void
    {
        $this->checkFailedMissingMandatory([
            'name' => self::$userData['name'],
            'email' => self::$userData['email'],
            'password' => self::$userData['password'],
        ]);
    }

    public function testFailedNonExistentGender(): void
    {
        $this->checkFailedValidationFailed([
            'gender' => 'gender',
            'name' => self::$userData['name'],
            'email' => self::$userData['email'],
            'password' => self::$userData['password'],
        ], [
            'gender',
        ]);
    }

    public function testFailedMissingName(): void
    {
        $this->checkFailedMissingMandatory([
            'gender' => self::$userData['gender'],
            'email' => self::$userData['email'],
            'password' => self::$userData['password'],
        ]);
    }

    public function testFailedMissingEmail(): void
    {
        $this->checkFailedMissingMandatory([
            'gender' => self::$userData['gender'],
            'name' => self::$userData['name'],
            'password' => self::$userData['password'],
        ]);
    }

    public function testFailedWrongFormatEmail(): void
    {
        $this->checkFailedValidationFailed([
            'gender' => self::$userData['gender'],
            'name' => self::$userData['name'],
            'email' => 'email',
            'password' => self::$userData['password'],
        ], [
            'email',
        ]);
    }

    public function testFailedNonExistentEmail(): void
    {
        $this->checkFailedValidationFailed([
            'gender' => self::$userData['gender'],
            'name' => self::$userData['name'],
            'email' => 'email@email.em',
            'password' => self::$userData['password'],
        ], [
            'email',
        ]);
    }

    public function testFailedNonUniqueEmail(): void
    {
        $this->checkFailedValidationFailed([
            'gender' => self::$userData['gender'],
            'name' => self::$userData['name'],
            'email' => 'hello@pinkstory.io',
            'password' => self::$userData['password'],
        ], [
            'email',
        ]);
    }

    public function testFailedMissingPassword(): void
    {
        $this->checkFailedMissingMandatory([
            'gender' => self::$userData['gender'],
            'name' => self::$userData['name'],
            'email' => self::$userData['email'],
        ]);
    }

    public function testFailedPasswordStrenght(): void
    {
        $this->checkFailedValidationFailed([
            'gender' => self::$userData['gender'],
            'name' => self::$userData['name'],
            'email' => self::$userData['email'],
            'password' => 'password',
        ], [
            'password',
        ]);
    }

    protected function checkProcessHasBeenSucceeded(array $options = []): void
    {
        // get fresh user from database
        $user = $this->userRepository->findOneByEmail(self::$userData['email']);
        $this->entityManager->refresh($user);

        // check user has been created
        $this->assertTrue(Uuid::isValid($user->getId()));
        $this->assertEquals(self::$userData['gender'], $user->getGender());
        $this->assertEquals(self::$userData['name'], $user->getName());
        $this->assertEquals(self::$userData['email'], $user->getEmail());
        $this->assertFalse($user->isEmailValidated());
        $this->assertRegExp('/([0-9]{6})/', $user->getEmailValidationCode());
        $this->assertFalse($user->isEmailValidationCodeUsed());
        $this->assertTrue(self::$container->get(UserPasswordEncoderInterface::class)->isPasswordValid($user, self::$userData['password']));
        $this->assertEquals($options['should_have_image_defined'], $user->isImageDefined());
        $this->assertEquals(UserRole::USER, $user->getRole());
        $this->assertEquals(UserStatus::ACTIVATED, $user->getStatus());

        // check image has been uploaded
        if (true === $options['should_have_image_defined']) {
            $user = $this->userRepository->findOneByEmail(self::$userData['email']);
            $this->assertTrue((new Filesystem())->exists(self::$container->getParameter('project_image_storage_path').$user->getImagePath()));
        }

        // check event has been dispatched
        $this->assertCount(1, $this->asyncTransport->get());
        $this->assertInstanceOf(UserCreatedEvent::class, $this->asyncTransport->get()[0]->getMessage());
        $this->assertEquals($user->getId(), $this->asyncTransport->get()[0]->getMessage()->getId());
        $this->assertEquals($user->getGender(), $this->asyncTransport->get()[0]->getMessage()->getGender());
        $this->assertEquals($user->getName(), $this->asyncTransport->get()[0]->getMessage()->getName());
        $this->assertEquals($user->getEmail(), $this->asyncTransport->get()[0]->getMessage()->getEmail());
        $this->assertEquals($user->getEmailValidationCode(), $this->asyncTransport->get()[0]->getMessage()->getEmailValidationCode());
        $this->assertEquals($user->getPassword(), $this->asyncTransport->get()[0]->getMessage()->getPassword());
        $this->assertEquals($user->getImagePath(), $this->asyncTransport->get()[0]->getMessage()->getImagePath());
        $this->assertEquals($user->getRole(), $this->asyncTransport->get()[0]->getMessage()->getRole());
        $this->assertEquals($user->getStatus(), $this->asyncTransport->get()[0]->getMessage()->getStatus());
    }

    protected function checkProcessHasBeenStopped(): void
    {
        try {
            // get fresh user from database
            $user = $this->userRepository->findOneByEmail(self::$userData['email']);
            $this->entityManager->refresh($user);
            $this->fail();
        } catch (NoResultException $e) {
            $this->assertTrue(true);
        }

        // check event has not been dispatched
        $this->assertCount(0, $this->asyncTransport->get());
    }
}
