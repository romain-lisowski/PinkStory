<?php

declare(strict_types=1);

namespace App\Test\User\Presentation\Action;

use App\Common\Infrastructure\Serializer\Normalizer\DataUriNormalizer;
use App\User\Domain\Event\UserCreatedEvent;
use App\User\Domain\Model\UserGender;
use App\User\Domain\Security\UserPasswordEncoderInterface;
use Doctrine\ORM\NoResultException;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\File;

/**
 * @internal
 * @coversNothing
 */
final class AccountSignupActionTest extends AbastractUserActionTest
{
    private const USER_DATA = [
        'gender' => UserGender::UNDEFINED,
        'name' => 'Test',
        'email' => 'test@pinkstory.io',
        'password' => '@Password2!',
    ];

    public function testSuccessWithoutImage(): void
    {
        $this->client->request('POST', '/account/signup', [], [], [], json_encode([
            'gender' => self::USER_DATA['gender'],
            'name' => self::USER_DATA['name'],
            'email' => self::USER_DATA['email'],
            'password' => self::USER_DATA['password'],
        ]));

        // check http response
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertEquals(json_decode($this->client->getResponse()->getContent(), true), []);

        $this->assertTrue($this->hasDataBeenSavedInDatabase());
        $this->hasDataBeenFullySavedInDatabase(false);

        // check event has been dispatched
        $this->assertCount(1, $this->asyncTransport->get());
        $this->assertInstanceOf(UserCreatedEvent::class, $this->asyncTransport->get()[0]->getMessage());
    }

    public function testSuccessWithImage(): void
    {
        $this->client->request('POST', '/account/signup', [], [], [], json_encode([
            'gender' => self::USER_DATA['gender'],
            'name' => self::USER_DATA['name'],
            'email' => self::USER_DATA['email'],
            'password' => self::USER_DATA['password'],
            'image' => (new DataUriNormalizer())->normalize(new File(__DIR__.'/../../../image/test.jpg'), ''),
        ]));

        // check http response
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertEquals(json_decode($this->client->getResponse()->getContent(), true), []);

        $this->assertTrue($this->hasDataBeenSavedInDatabase());
        $this->hasDataBeenFullySavedInDatabase(true);

        // check image has been uploaded
        $user = $this->userRepository->findOneByEmail(self::USER_DATA['email']);
        $this->assertTrue((new Filesystem())->exists(self::$container->getParameter('project_image_storage_path').$user->getImagePath()));

        // check event has been dispatched
        $this->assertCount(1, $this->asyncTransport->get());
        $this->assertInstanceOf(UserCreatedEvent::class, $this->asyncTransport->get()[0]->getMessage());
    }

    public function testFailedNonExistentGender(): void
    {
        $this->client->request('POST', '/account/signup', [], [], [], json_encode([
            'gender' => 'gender',
            'name' => self::USER_DATA['name'],
            'email' => self::USER_DATA['email'],
            'password' => self::USER_DATA['password'],
        ]));

        // check http response
        $this->assertEquals(400, $this->client->getResponse()->getStatusCode());
        $responseContent = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertEquals($responseContent['exception']['type'], 'validation_failed_exception');
        $this->assertEquals($responseContent['exception']['violations'][0]['property_path'], 'gender');

        $this->assertFalse($this->hasDataBeenSavedInDatabase());

        // check event has not been dispatched
        $this->assertCount(0, $this->asyncTransport->get());
    }

    public function testFailedWrongFormatEmail(): void
    {
        $this->client->request('POST', '/account/signup', [], [], [], json_encode([
            'gender' => self::USER_DATA['gender'],
            'name' => self::USER_DATA['name'],
            'email' => 'email',
            'password' => self::USER_DATA['password'],
        ]));

        // check http response
        $this->assertEquals(400, $this->client->getResponse()->getStatusCode());
        $responseContent = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertEquals($responseContent['exception']['type'], 'validation_failed_exception');
        $this->assertEquals($responseContent['exception']['violations'][0]['property_path'], 'email');

        $this->assertFalse($this->hasDataBeenSavedInDatabase());

        // check event has not been dispatched
        $this->assertCount(0, $this->asyncTransport->get());
    }

    public function testFailedNonExistentEmail(): void
    {
        $this->client->request('POST', '/account/signup', [], [], [], json_encode([
            'gender' => self::USER_DATA['gender'],
            'name' => self::USER_DATA['name'],
            'email' => 'email@email.em',
            'password' => self::USER_DATA['password'],
        ]));

        // check http response
        $this->assertEquals(400, $this->client->getResponse()->getStatusCode());
        $responseContent = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertEquals($responseContent['exception']['type'], 'validation_failed_exception');
        $this->assertEquals($responseContent['exception']['violations'][0]['property_path'], 'email');

        $this->assertFalse($this->hasDataBeenSavedInDatabase());

        // check event has not been dispatched
        $this->assertCount(0, $this->asyncTransport->get());
    }

    public function testFailedNonUniqueEmail(): void
    {
        $this->client->request('POST', '/account/signup', [], [], [], json_encode([
            'gender' => self::USER_DATA['gender'],
            'name' => self::USER_DATA['name'],
            'email' => 'hello@pinkstory.io',
            'password' => self::USER_DATA['password'],
        ]));

        // check http response
        $this->assertEquals(400, $this->client->getResponse()->getStatusCode());
        $responseContent = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertEquals($responseContent['exception']['type'], 'validation_failed_exception');
        $this->assertEquals($responseContent['exception']['violations'][0]['property_path'], 'email');

        $this->assertFalse($this->hasDataBeenSavedInDatabase());

        // check event has not been dispatched
        $this->assertCount(0, $this->asyncTransport->get());
    }

    public function testFailedPasswordStrenght(): void
    {
        $this->client->request('POST', '/account/signup', [], [], [], json_encode([
            'gender' => self::USER_DATA['gender'],
            'name' => self::USER_DATA['name'],
            'email' => self::USER_DATA['email'],
            'password' => 'password',
        ]));

        // check http response
        $this->assertEquals(400, $this->client->getResponse()->getStatusCode());
        $responseContent = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertEquals($responseContent['exception']['type'], 'validation_failed_exception');
        $this->assertEquals($responseContent['exception']['violations'][0]['property_path'], 'password');

        $this->assertFalse($this->hasDataBeenSavedInDatabase());

        // check event has not been dispatched
        $this->assertCount(0, $this->asyncTransport->get());
    }

    private function hasDataBeenSavedInDatabase(): bool
    {
        try {
            $this->userRepository->findOneByEmail(self::USER_DATA['email']);

            return true;
        } catch (NoResultException $e) {
            return false;
        }
    }

    private function hasDataBeenFullySavedInDatabase($shouldHaveImageDefined = false): void
    {
        $user = $this->userRepository->findOneByEmail(self::USER_DATA['email']);

        $this->assertEquals($user->getGender(), self::USER_DATA['gender']);
        $this->assertEquals($user->getName(), self::USER_DATA['name']);
        $this->assertEquals($user->getEmail(), self::USER_DATA['email']);
        $this->assertFalse($user->isEmailValidated());
        $this->assertRegExp('/([0-9]{6})/', $user->getEmailValidationCode());
        $this->assertFalse($user->isEmailValidationCodeUsed());
        $this->assertTrue(self::$container->get(UserPasswordEncoderInterface::class)->isPasswordValid($user, self::USER_DATA['password']));
        $this->assertEquals($user->isImageDefined(), $shouldHaveImageDefined);
    }
}
