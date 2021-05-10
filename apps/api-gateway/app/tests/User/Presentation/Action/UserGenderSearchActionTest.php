<?php

declare(strict_types=1);

namespace App\Test\User\Presentation\Action;

use App\Common\Domain\Translation\TranslatorInterface;
use App\Fixture\Language\LanguageFixture;
use App\Fixture\User\AccessTokenFixture;
use App\Fixture\User\UserFixture;
use App\User\Domain\Model\UserGender;
use Symfony\Component\HttpFoundation\Request;

/**
 * @internal
 * @coversNothing
 */
final class UserGenderSearchActionTest extends AbstractUserActionTest
{
    protected function setUp(): void
    {
        self::$httpMethod = Request::METHOD_GET;
        self::$httpUri = '/user-gender/search';
        self::$httpAuthorizationToken = null;

        parent::setUp();
    }

    public function testSucceededNoLogginButEnglish(): void
    {
        $this->checkSucceeded([], [
            'language_reference' => 'language-english',
        ]);
    }

    public function testSucceededNoLogginButFrench(): void
    {
        // change locale
        self::$httpUri = '/user-gender/search?_locale=fr';

        $this->checkSucceeded([], [
            'language_reference' => 'language-french',
        ]);
    }

    public function testSucceededLogginEnglish(): void
    {
        // change locale (force to test user setting override)
        self::$httpUri = '/user-gender/search?_locale=fr';

        // change user logged in
        self::$httpAuthorizationToken = AccessTokenFixture::DATA['access-token-john']['id'];

        $this->checkSucceeded([], [
            'language_reference' => UserFixture::DATA['user-john']['language_reference'],
        ]);
    }

    public function testSucceededLogginFrench(): void
    {
        // change user logged in
        self::$httpAuthorizationToken = AccessTokenFixture::DATA['access-token-pinkstory']['id'];

        $this->checkSucceeded([], [
            'language_reference' => UserFixture::DATA['user-pinkstory']['language_reference'],
        ]);
    }

    protected function checkProcessHasBeenSucceeded(array $responseData = [], array $options = []): void
    {
        $this->assertCount(count(UserGender::getChoices()), $responseData['user_genders']);

        foreach ($responseData['user_genders'] as $key => $value) {
            $this->assertEquals(self::$container->get(TranslatorInterface::class)->trans(strtolower(UserGender::getTranslationPrefix().$key), [], null, LanguageFixture::DATA[$options['language_reference']]['locale']), $value);
        }
    }

    protected function checkProcessHasBeenStopped(array $responseData = [], array $options = []): void
    {
        // nothing to check
    }
}
