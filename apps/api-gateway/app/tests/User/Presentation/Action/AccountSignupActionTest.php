<?php

declare(strict_types=1);

namespace App\Test\User\Presentation\Action;

use App\Common\Infrastructure\Serializer\Normalizer\DataUriNormalizer;
use App\User\Domain\Model\UserGender;
use App\User\Domain\Security\UserPasswordEncoderInterface;
use Doctrine\ORM\NoResultException;
use Doctrine\ORM\UnexpectedResultException;
use Symfony\Bridge\Twig\Mime\NotificationEmail;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Mime\Address;

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
        $this->assertEquals(json_encode([]), $this->client->getResponse()->getContent());

        $this->assertTrue($this->hasDataBeenSavedInDatabase());
        $this->assertTrue($this->hasDataBeenFullySavedInDatabase(false));

        $this->assertTrue($this->hasMailBeenSent());
        $this->assertTrue($this->hasMailBeenFullySent());
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
        $this->assertEquals(json_encode([]), $this->client->getResponse()->getContent());

        $this->assertTrue($this->hasDataBeenSavedInDatabase());
        $this->assertTrue($this->hasDataBeenFullySavedInDatabase(true));

        // check image has been uploaded
        $user = $this->userRepository->findOneByEmail(self::USER_DATA['email']);
        $this->assertTrue((new Filesystem())->exists(self::$container->getParameter('project_image_storage_path').$user->getImagePath()));

        $this->assertTrue($this->hasMailBeenSent());
        $this->assertTrue($this->hasMailBeenFullySent());
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
        $this->assertEquals(500, $this->client->getResponse()->getStatusCode());
        // TODO: check response body content

        $this->assertFalse($this->hasDataBeenSavedInDatabase());

        $this->assertFalse($this->hasMailBeenSent());
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
        $this->assertEquals(500, $this->client->getResponse()->getStatusCode());
        // TODO: check response body content

        $this->assertFalse($this->hasDataBeenSavedInDatabase());

        $this->assertFalse($this->hasMailBeenSent());
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
        $this->assertEquals(500, $this->client->getResponse()->getStatusCode());
        // TODO: check response body content

        $this->assertFalse($this->hasDataBeenSavedInDatabase());

        $this->assertFalse($this->hasMailBeenSent());
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
        $this->assertEquals(500, $this->client->getResponse()->getStatusCode());
        // TODO: check response body content

        $this->assertFalse($this->hasDataBeenSavedInDatabase());

        $this->assertFalse($this->hasMailBeenSent());
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
        $this->assertEquals(500, $this->client->getResponse()->getStatusCode());
        // TODO: check response body content

        $this->assertFalse($this->hasDataBeenSavedInDatabase());

        $this->assertFalse($this->hasMailBeenSent());
    }

    private function hasDataBeenSavedInDatabase(): bool
    {
        try {
            $user = $this->userRepository->findOneByEmail(self::USER_DATA['email']);

            return true;
        } catch (NoResultException $e) {
            return false;
        }
    }

    private function hasDataBeenFullySavedInDatabase($shouldHaveImageDefined = false): bool
    {
        try {
            $user = $this->userRepository->findOneByEmail(self::USER_DATA['email']);

            if (
                $user->getGender() !== self::USER_DATA['gender']
                || $user->getName() !== self::USER_DATA['name']
                || $user->getEmail() !== self::USER_DATA['email']
                || false === self::$container->get(UserPasswordEncoderInterface::class)->isPasswordValid($user, self::USER_DATA['password'])
                || $user->isImageDefined() !== $shouldHaveImageDefined
            ) {
                throw new UnexpectedResultException();
            }

            return true;
        } catch (UnexpectedResultException $e) {
            return false;
        }
    }

    private function hasMailBeenSent(): bool
    {
        $mailerCollector = $this->client->getProfile()->getCollector('mailer');

        if (1 !== count($mailerCollector->getEvents()->getMessages())) {
            return false;
        }

        return true;
    }

    private function hasMailBeenFullySent(): bool
    {
        $mailerCollector = $this->client->getProfile()->getCollector('mailer');

        if (1 !== count($mailerCollector->getEvents()->getMessages())) {
            return false;
        }

        $collectedMessages = $mailerCollector->getEvents()->getMessages();
        $message = $collectedMessages[0];

        if (
            !$message instanceof NotificationEmail
            || false === in_array(self::USER_DATA['email'], array_map(function (Address $address) { return $address->getAddress(); }, $message->getTo()))
        ) {
            return false;
        }

        return true;
    }
}
