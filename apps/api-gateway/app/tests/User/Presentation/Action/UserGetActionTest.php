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
        self::$httpMethod = Request::METHOD_GET;
        self::$httpUri = '/user/'.UserFixture::DATA['user-john']['id'];
        self::$httpAuthorizationToken = null;

        parent::setUp();
    }

    public function testSucceededSameUserLoggedIn(): void
    {
        // change user logged in
        self::$httpAuthorizationToken = AccessTokenFixture::DATA['access-token-john']['id'];

        $this->checkSucceeded([], [
            'language_reference' => UserFixture::DATA['user-john']['language_reference'],
            'image_defined' => false,
            'editable' => true,
            'language_editable' => false,
        ]);
    }

    public function testSucceededDifferentUserLoggedInButAdmin(): void
    {
        // change user logged in
        self::$httpAuthorizationToken = AccessTokenFixture::DATA['access-token-yannis']['id'];

        $this->checkSucceeded([], [
            'language_reference' => UserFixture::DATA['user-yannis']['language_reference'],
            'image_defined' => false,
            'editable' => true,
            'language_editable' => true,
        ]);
    }

    public function testSucceededDifferentUserLoggedInButModerator(): void
    {
        // change user logged in
        self::$httpAuthorizationToken = AccessTokenFixture::DATA['access-token-leslie']['id'];

        $this->checkSucceeded([], [
            'language_reference' => UserFixture::DATA['user-leslie']['language_reference'],
            'image_defined' => false,
            'editable' => true,
            'language_editable' => true,
        ]);
    }

    public function testSucceededDifferentUserLoggedIn(): void
    {
        // change user logged in
        self::$httpAuthorizationToken = AccessTokenFixture::DATA['access-token-juliette']['id'];

        $this->checkSucceeded([], [
            'language_reference' => UserFixture::DATA['user-juliette']['language_reference'],
            'image_defined' => false,
            'editable' => false,
            'language_editable' => false,
        ]);
    }

    public function testSucceededNoUserLoggedInButEnglish(): void
    {
        $this->checkSucceeded([], [
            'language_reference' => 'language-english',
            'image_defined' => false,
            'editable' => false,
            'language_editable' => false,
        ]);
    }

    public function testSucceededNoUserLoggedInButEnglishWithImage(): void
    {
        // set image to user
        $user = $this->userRepository->findOne(UserFixture::DATA['user-john']['id']);
        $user->setImageDefined(true);
        $this->userRepository->flush();

        $this->checkSucceeded([], [
            'language_reference' => 'language-english',
            'image_defined' => true,
            'editable' => false,
            'language_editable' => false,
        ]);
    }

    public function testSucceededNoUserLoggedInButFrench(): void
    {
        // change locale
        self::$httpUri = '/user/'.UserFixture::DATA['user-john']['id'].'?_locale=fr';

        $this->checkSucceeded([], [
            'language_reference' => 'language-french',
            'image_defined' => false,
            'editable' => false,
            'language_editable' => false,
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
        $this->assertEquals(self::$container->get(TranslatorInterface::class)->trans(strtolower(UserGender::getTranslationPrefix().UserFixture::DATA['user-john']['gender']), [], null, LanguageFixture::DATA[$options['language_reference']]['locale']), $responseData['user']['gender_reading']);
        $this->assertEquals(UserFixture::DATA['user-john']['name'], $responseData['user']['name']);
        $this->assertEquals((new AsciiSlugger())->slug(UserFixture::DATA['user-john']['name'])->lower()->toString(), $responseData['user']['name_slug']);
        $this->assertEquals($options['image_defined'], is_string($responseData['user']['image_url']));
        $this->assertTrue(new DateTime() > new DateTime($responseData['user']['created_at']));
        $this->assertEquals($options['editable'], $responseData['user']['editable']);
        $this->assertEquals(LanguageFixture::DATA[UserFixture::DATA['user-john']['language_reference']]['id'], $responseData['user']['language']['id']);
        $this->assertEquals(LanguageFixture::DATA[UserFixture::DATA['user-john']['language_reference']]['title'], $responseData['user']['language']['title']);
        $this->assertEquals(LanguageFixture::DATA[UserFixture::DATA['user-john']['language_reference']]['locale'], $responseData['user']['language']['locale']);
        $this->assertIsString($responseData['user']['language']['image_url']);
        $this->assertEquals($options['language_editable'], $responseData['user']['language']['editable']);

        $this->assertCount(count(UserFixture::DATA['user-john']['reading_language_references']), $responseData['user']['reading_languages']);

        foreach (UserFixture::DATA['user-john']['reading_language_references'] as $readingLanguageReference) {
            $exists = false;

            foreach ($responseData['user']['reading_languages'] as $readingLanguage) {
                if (LanguageFixture::DATA[$readingLanguageReference]['id'] === $readingLanguage['id']) {
                    $this->assertEquals(LanguageFixture::DATA[$readingLanguageReference]['title'], $readingLanguage['title']);
                    $this->assertEquals(LanguageFixture::DATA[$readingLanguageReference]['locale'], $readingLanguage['locale']);
                    $this->assertIsString($readingLanguage['image_url']);
                    $this->assertEquals($options['language_editable'], $readingLanguage['editable']);

                    $exists = true;

                    break;
                }
            }

            if (false === $exists) {
                $this->fail('Reading language ['.LanguageFixture::DATA[$readingLanguageReference]['id'].'] does not exist.');
            }
        }
    }

    protected function checkProcessHasBeenStopped(array $responseData = [], array $options = []): void
    {
        // nothing to check
    }
}
