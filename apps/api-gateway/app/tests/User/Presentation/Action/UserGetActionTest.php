<?php

declare(strict_types=1);

namespace App\Test\User\Presentation\Action;

use App\Common\Domain\Translation\TranslatorInterface;
use App\Fixture\Language\LanguageFixture;
use App\Fixture\User\AccessTokenFixture;
use App\Fixture\User\UserFixture;
use App\User\Domain\Model\UserGender;
use App\User\Domain\Model\UserStatus;
use DateTime;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\String\Slugger\AsciiSlugger;
use Symfony\Component\Uid\Uuid;

/**
 * @internal
 * @coversNothing
 */
final class UserGetActionTest extends AbstractUserActionTest
{
    protected function setUp(): void
    {
        parent::setUp();

        self::$httpMethod = Request::METHOD_GET;
        self::$httpUri = '/user/'.UserFixture::DATA['user-john']['id'];
        self::$httpAuthorization = 'Bearer '.AccessTokenFixture::DATA['access-token-john']['id'];
    }

    public function testSuccessSameUserLoggedIn(): void
    {
        $this->checkSuccess([], [
            'editable' => true,
        ]);
    }

    public function testSuccessDifferentUserLoggedInButAdmin(): void
    {
        // change user logged in
        self::$httpAuthorization = 'Bearer '.AccessTokenFixture::DATA['access-token-yannis']['id'];

        $this->checkSuccess([], [
            'editable' => true,
        ]);
    }

    public function testSuccessDifferentUserLoggedInButModerator(): void
    {
        // change user logged in
        self::$httpAuthorization = 'Bearer '.AccessTokenFixture::DATA['access-token-leslie']['id'];

        $this->checkSuccess([], [
            'editable' => true,
        ]);
    }

    public function testSuccessDifferentUserLoggedIn(): void
    {
        // change user logged in
        self::$httpAuthorization = 'Bearer '.AccessTokenFixture::DATA['access-token-juliette']['id'];

        $this->checkSuccess([], [
            'editable' => false,
        ]);
    }

    public function testSuccessNoUserLoggedIn(): void
    {
        // no user logged in
        self::$httpAuthorization = null;

        $this->checkSuccess([], [
            'editable' => false,
        ]);
    }

    public function testFailedNotFoundWrongFormatId(): void
    {
        // wrong uri format
        self::$httpUri = '/user/id';

        $this->checkFailedNotFound();
    }

    public function testFailedNotFoundNonExistentId(): void
    {
        // non existent id
        self::$httpUri = '/user/'.Uuid::v4()->toRfc4122();

        $this->checkFailedNotFound();
    }

    public function testFailedNotFoundUserBlocked(): void
    {
        self::$httpUri = '/user/'.UserFixture::DATA['user-john']['id'];

        // block user
        $user = $this->userRepository->findOne(UserFixture::DATA['user-john']['id']);
        $user->setStatus(UserStatus::BLOCKED);
        $this->userRepository->flush();

        $this->checkFailedNotFound();
    }

    protected function checkProcessHasBeenSucceeded(array $responseData = [], array $options = []): void
    {
        $this->assertEquals(UserFixture::DATA['user-john']['id'], $responseData['user']['id']);
        $this->assertEquals(UserFixture::DATA['user-john']['gender'], $responseData['user']['gender']);
        $this->assertEquals(UserGender::getReadingChoice(UserFixture::DATA['user-john']['gender'], self::$container->get(TranslatorInterface::class)), $responseData['user']['gender_reading']);
        $this->assertEquals(UserFixture::DATA['user-john']['name'], $responseData['user']['name']);
        $this->assertEquals((new AsciiSlugger())->slug(UserFixture::DATA['user-john']['name'])->lower()->toString(), $responseData['user']['name_slug']);
        $this->assertFalse($responseData['user']['image_defined']);
        $this->assertEquals(LanguageFixture::DATA[UserFixture::DATA['user-john']['language_reference']]['id'], $responseData['user']['language']['id']);
        $this->assertEquals(LanguageFixture::DATA[UserFixture::DATA['user-john']['language_reference']]['title'], $responseData['user']['language']['title']);
        $this->assertEquals(LanguageFixture::DATA[UserFixture::DATA['user-john']['language_reference']]['locale'], $responseData['user']['language']['locale']);
        $this->assertTrue(new DateTime() > new DateTime($responseData['user']['created_at']));
        $this->assertEquals($options['editable'], $responseData['user']['editable']);
    }

    protected function checkProcessHasBeenStopped(): void
    {
        // nothing to check
    }
}
