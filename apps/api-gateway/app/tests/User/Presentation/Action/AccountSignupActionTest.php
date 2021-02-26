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
    protected const HTTP_METHOD = Request::METHOD_POST;
    protected const HTTP_URI = '/account/signup';
    protected const HTTP_AUTHORIZATION = false;

    private const USER_DATA = [
        'gender' => UserGender::UNDEFINED,
        'name' => 'Test',
        'email' => 'test@pinkstory.io',
        'password' => '@Password2!',
    ];

    public function testSuccessWithoutImage(): void
    {
        $this->checkSuccess([
            'gender' => self::USER_DATA['gender'],
            'name' => self::USER_DATA['name'],
            'email' => self::USER_DATA['email'],
            'password' => self::USER_DATA['password'],
        ], [], ['should_have_image_defined' => false]);
    }

    public function testSuccessWithImage(): void
    {
        $this->checkSuccess([
            'gender' => self::USER_DATA['gender'],
            'name' => self::USER_DATA['name'],
            'email' => self::USER_DATA['email'],
            'password' => self::USER_DATA['password'],
            'image' => (new DataUriNormalizer())->normalize(new File(__DIR__.'/../../../image/test.jpg'), ''),
        ], [], ['should_have_image_defined' => true]);
    }

    public function testFailedMissingGender(): void
    {
        $this->checkFailedMissingMandatory([
            'name' => self::USER_DATA['name'],
            'email' => self::USER_DATA['email'],
            'password' => self::USER_DATA['password'],
        ]);
    }

    public function testFailedNonExistentGender(): void
    {
        $this->checkFailedValidationFailed([
            'gender' => 'gender',
            'name' => self::USER_DATA['name'],
            'email' => self::USER_DATA['email'],
            'password' => self::USER_DATA['password'],
        ], [
            'gender',
        ]);
    }

    public function testFailedMissingName(): void
    {
        $this->checkFailedMissingMandatory([
            'gender' => self::USER_DATA['gender'],
            'email' => self::USER_DATA['email'],
            'password' => self::USER_DATA['password'],
        ]);
    }

    public function testFailedMissingEmail(): void
    {
        $this->checkFailedMissingMandatory([
            'gender' => self::USER_DATA['gender'],
            'name' => self::USER_DATA['name'],
            'password' => self::USER_DATA['password'],
        ]);
    }

    public function testFailedWrongFormatEmail(): void
    {
        $this->checkFailedValidationFailed([
            'gender' => self::USER_DATA['gender'],
            'name' => self::USER_DATA['name'],
            'email' => 'email',
            'password' => self::USER_DATA['password'],
        ], [
            'email',
        ]);
    }

    public function testFailedNonExistentEmail(): void
    {
        $this->checkFailedValidationFailed([
            'gender' => self::USER_DATA['gender'],
            'name' => self::USER_DATA['name'],
            'email' => 'email@email.em',
            'password' => self::USER_DATA['password'],
        ], [
            'email',
        ]);
    }

    public function testFailedNonUniqueEmail(): void
    {
        $this->checkFailedValidationFailed([
            'gender' => self::USER_DATA['gender'],
            'name' => self::USER_DATA['name'],
            'email' => 'hello@pinkstory.io',
            'password' => self::USER_DATA['password'],
        ], [
            'email',
        ]);
    }

    public function testFailedMissingPassword(): void
    {
        $this->checkFailedMissingMandatory([
            'gender' => self::USER_DATA['gender'],
            'name' => self::USER_DATA['name'],
            'email' => self::USER_DATA['email'],
        ]);
    }

    public function testFailedPasswordStrenght(): void
    {
        $this->checkFailedValidationFailed([
            'gender' => self::USER_DATA['gender'],
            'name' => self::USER_DATA['name'],
            'email' => self::USER_DATA['email'],
            'password' => 'password',
        ], [
            'password',
        ]);
    }

    protected function checkProcessHasBeenSucceeded(array $options = []): void
    {
        // get fresh user from database
        $user = $this->userRepository->findOneByEmail(self::USER_DATA['email']);
        $this->entityManager->refresh($user);

        // check user has been created
        $this->assertTrue(Uuid::isValid($user->getId()));
        $this->assertEquals(self::USER_DATA['gender'], $user->getGender());
        $this->assertEquals(self::USER_DATA['name'], $user->getName());
        $this->assertEquals(self::USER_DATA['email'], $user->getEmail());
        $this->assertFalse($user->isEmailValidated());
        $this->assertRegExp('/([0-9]{6})/', $user->getEmailValidationCode());
        $this->assertFalse($user->isEmailValidationCodeUsed());
        $this->assertTrue(self::$container->get(UserPasswordEncoderInterface::class)->isPasswordValid($user, self::USER_DATA['password']));
        $this->assertEquals($options['should_have_image_defined'], $user->isImageDefined());
        $this->assertEquals(UserRole::USER, $user->getRole());
        $this->assertEquals(UserStatus::ACTIVATED, $user->getStatus());

        // check image has been uploaded
        if (true === $options['should_have_image_defined']) {
            $user = $this->userRepository->findOneByEmail(self::USER_DATA['email']);
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
            $user = $this->userRepository->findOneByEmail(self::USER_DATA['email']);
            $this->entityManager->refresh($user);
            $this->fail();
        } catch (NoResultException $e) {
            $this->assertTrue(true);
        }

        // check event has not been dispatched
        $this->assertCount(0, $this->asyncTransport->get());
    }
}
