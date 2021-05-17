<?php

declare(strict_types=1);

namespace App\Test\User\Presentation\Action;

use App\Common\Domain\Repository\NoResultException;
use App\Common\Infrastructure\Serializer\Normalizer\DataUriNormalizer;
use App\Fixture\Language\LanguageFixture;
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
final class AccountSignupActionTest extends AbstractUserActionTest
{
    private static array $userData = [
        'gender' => UserGender::UNDEFINED,
        'name' => 'Test',
        'email' => 'test@pinkstory.io',
        'password' => '@Password2!',
        'language_id' => LanguageFixture::DATA['language-french']['id'],
    ];

    protected function setUp(): void
    {
        self::$httpMethod = Request::METHOD_POST;
        self::$httpUri = '/account/signup';
        self::$httpAuthorizationToken = null;

        parent::setUp();
    }

    public function testSucceededWithoutImage(): void
    {
        $this->checkSucceeded([
            'gender' => self::$userData['gender'],
            'name' => self::$userData['name'],
            'email' => self::$userData['email'],
            'password' => self::$userData['password'],
            'language_id' => self::$userData['language_id'],
        ], ['should_have_image_defined' => false]);
    }

    public function testSucceededWithImage(): void
    {
        $this->checkSucceeded([
            'gender' => self::$userData['gender'],
            'name' => self::$userData['name'],
            'email' => self::$userData['email'],
            'password' => self::$userData['password'],
            'image' => (new DataUriNormalizer())->normalize(new File(__DIR__.'/../../../image/test.jpg'), ''),
            'language_id' => self::$userData['language_id'],
        ], ['should_have_image_defined' => true]);
    }

    public function testFailedMissingGender(): void
    {
        $this->checkFailedValidationFailed([
            'name' => self::$userData['name'],
            'email' => self::$userData['email'],
            'password' => self::$userData['password'],
            'language_id' => self::$userData['language_id'],
        ], [
            'gender',
        ]);
    }

    public function testFailedNonExistentGender(): void
    {
        $this->checkFailedValidationFailed([
            'gender' => 'gender',
            'name' => self::$userData['name'],
            'email' => self::$userData['email'],
            'password' => self::$userData['password'],
            'language_id' => self::$userData['language_id'],
        ], [
            'gender',
        ]);
    }

    public function testFailedMissingName(): void
    {
        $this->checkFailedValidationFailed([
            'gender' => self::$userData['gender'],
            'email' => self::$userData['email'],
            'password' => self::$userData['password'],
            'language_id' => self::$userData['language_id'],
        ], [
            'name',
        ]);
    }

    public function testFailedMissingEmail(): void
    {
        $this->checkFailedValidationFailed([
            'gender' => self::$userData['gender'],
            'name' => self::$userData['name'],
            'password' => self::$userData['password'],
            'language_id' => self::$userData['language_id'],
        ], [
            'email',
        ]);
    }

    public function testFailedWrongFormatEmail(): void
    {
        $this->checkFailedValidationFailed([
            'gender' => self::$userData['gender'],
            'name' => self::$userData['name'],
            'email' => 'email',
            'password' => self::$userData['password'],
            'language_id' => self::$userData['language_id'],
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
            'language_id' => self::$userData['language_id'],
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
            'language_id' => self::$userData['language_id'],
        ], [
            'email',
        ]);
    }

    public function testFailedMissingPassword(): void
    {
        $this->checkFailedValidationFailed([
            'gender' => self::$userData['gender'],
            'name' => self::$userData['name'],
            'email' => self::$userData['email'],
            'language_id' => self::$userData['language_id'],
        ], [
            'password',
        ]);
    }

    public function testFailedPasswordStrenght(): void
    {
        $this->checkFailedValidationFailed([
            'gender' => self::$userData['gender'],
            'name' => self::$userData['name'],
            'email' => self::$userData['email'],
            'password' => 'password',
            'language_id' => self::$userData['language_id'],
        ], [
            'password',
        ]);
    }

    public function testFailedMissingLanguage(): void
    {
        $this->checkFailedValidationFailed([
            'gender' => self::$userData['gender'],
            'name' => self::$userData['name'],
            'email' => self::$userData['email'],
            'password' => self::$userData['password'],
        ], [
            'language_id',
        ]);
    }

    public function testFailedWrongFormatLanguage(): void
    {
        $this->checkFailedValidationFailed([
            'gender' => self::$userData['gender'],
            'name' => self::$userData['name'],
            'email' => self::$userData['email'],
            'password' => self::$userData['password'],
            'language_id' => 'language_id',
        ], [
            'language_id',
        ]);
    }

    public function testFailedNonExistentLanguage(): void
    {
        $this->checkFailedValidationFailed([
            'gender' => self::$userData['gender'],
            'name' => self::$userData['name'],
            'email' => self::$userData['email'],
            'password' => self::$userData['password'],
            'language_id' => Uuid::v4()->toRfc4122(),
        ], [
            'language_id',
        ]);
    }

    protected function checkProcessHasBeenSucceeded(array $responseData = [], array $options = []): void
    {
        // check http response
        $this->assertTrue(Uuid::isValid($responseData['user']['id']));

        // get fresh user from database
        $user = $this->userRepository->findOne(Uuid::fromString($responseData['user']['id'])->toRfc4122());

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
        $this->assertEquals(self::$userData['language_id'], $user->getLanguage()->getId());
        $this->assertCount(1, $user->getUserHasReadingLanguages());
        $this->assertEquals(self::$userData['language_id'], $user->getUserHasReadingLanguages()->first()->getLanguage()->getId());

        // check image has been uploaded
        if (true === $options['should_have_image_defined']) {
            $user = $this->userRepository->findOneByEmail(self::$userData['email']);
            $this->assertTrue((new Filesystem())->exists(self::$container->getParameter('project_image_storage_path').$user->getImagePath()));
        }

        // check event has been dispatched
        $this->assertCount(1, $this->asyncTransport->get());
        $this->assertEquals($user->getId(), $this->asyncTransport->get()[0]->getMessage()->getId());
        $this->assertEquals($user->getGender(), $this->asyncTransport->get()[0]->getMessage()->getGender());
        $this->assertEquals($user->getName(), $this->asyncTransport->get()[0]->getMessage()->getName());
        $this->assertEquals($user->getEmail(), $this->asyncTransport->get()[0]->getMessage()->getEmail());
        $this->assertEquals($user->getEmailValidationCode(), $this->asyncTransport->get()[0]->getMessage()->getEmailValidationCode());
        $this->assertEquals($user->getPassword(), $this->asyncTransport->get()[0]->getMessage()->getPassword());
        $this->assertEquals($user->getImagePath(), $this->asyncTransport->get()[0]->getMessage()->getImagePath());
        $this->assertEquals($user->getRole(), $this->asyncTransport->get()[0]->getMessage()->getRole());
        $this->assertEquals($user->getStatus(), $this->asyncTransport->get()[0]->getMessage()->getStatus());
        $this->assertEquals($user->getLanguage()->getId(), $this->asyncTransport->get()[0]->getMessage()->getLanguageId());
        $this->assertCount(1, $this->asyncTransport->get()[0]->getMessage()->getReadingLanguageIds());
        $this->assertEquals($user->getLanguage()->getId(), $this->asyncTransport->get()[0]->getMessage()->getReadingLanguageIds()[0]);
    }

    protected function checkProcessHasBeenStopped(array $responseData = [], array $options = []): void
    {
        try {
            // get fresh user from database
            $user = $this->userRepository->findOneByEmail(self::$userData['email']);
            $this->fail();
        } catch (NoResultException $e) {
            $this->assertTrue(true);

            // check event has not been dispatched
            $this->assertCount(0, $this->asyncTransport->get());
        }
    }
}
