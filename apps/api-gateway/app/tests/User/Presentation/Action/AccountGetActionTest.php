<?php

declare(strict_types=1);

namespace App\Test\User\Presentation\Action;

use App\Common\Domain\Translation\TranslatorInterface;
use App\Fixture\Language\LanguageFixture;
use App\Fixture\User\AccessTokenFixture;
use App\Fixture\User\UserFixture;
use App\User\Domain\Model\UserGender;
use DateTime;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\String\Slugger\AsciiSlugger;

/**
 * @internal
 * @coversNothing
 */
final class AccountGetActionTest extends AbstractUserActionTest
{
    protected function setUp(): void
    {
        self::$httpMethod = Request::METHOD_GET;
        self::$httpUri = '/account';
        self::$httpAuthorizationToken = AccessTokenFixture::DATA['access-token-john']['id'];

        parent::setUp();
    }

    public function testSucceeded(): void
    {
        $this->checkSucceeded();
    }

    public function testFailedUnauthorized(): void
    {
        $this->checkFailedUnauthorized();
    }

    protected function checkProcessHasBeenSucceeded(array $responseData = [], array $options = []): void
    {
        $this->assertEquals(UserFixture::DATA['user-john']['id'], $responseData['user']['id']);
        $this->assertEquals(UserFixture::DATA['user-john']['gender'], $responseData['user']['gender']);
        $this->assertEquals(self::$container->get(TranslatorInterface::class)->trans(strtolower(UserGender::getTranslationPrefix().UserFixture::DATA['user-john']['gender']), [], null, LanguageFixture::DATA[UserFixture::DATA['user-john']['language_reference']]['locale']), $responseData['user']['gender_reading']);
        $this->assertEquals(UserFixture::DATA['user-john']['name'], $responseData['user']['name']);
        $this->assertEquals((new AsciiSlugger())->slug(UserFixture::DATA['user-john']['name'])->lower()->toString(), $responseData['user']['name_slug']);
        $this->assertNull($responseData['user']['image_url']);
        $this->assertTrue(new DateTime() > new DateTime($responseData['user']['created_at']));
        $this->assertTrue($responseData['user']['editable']);
        $this->assertEquals(LanguageFixture::DATA[UserFixture::DATA['user-john']['language_reference']]['id'], $responseData['user']['language']['id']);
        $this->assertEquals(LanguageFixture::DATA[UserFixture::DATA['user-john']['language_reference']]['title'], $responseData['user']['language']['title']);
        $this->assertEquals(LanguageFixture::DATA[UserFixture::DATA['user-john']['language_reference']]['locale'], $responseData['user']['language']['locale']);
        $this->assertIsString($responseData['user']['language']['image_url']);

        $this->assertCount(count(UserFixture::DATA['user-john']['reading_language_references']), $responseData['user']['reading_languages']);

        foreach (UserFixture::DATA['user-john']['reading_language_references'] as $readingLanguageReference) {
            $exists = false;

            foreach ($responseData['user']['reading_languages'] as $readingLanguage) {
                if (LanguageFixture::DATA[$readingLanguageReference]['id'] === $readingLanguage['id']) {
                    $this->assertEquals(LanguageFixture::DATA[$readingLanguageReference]['title'], $readingLanguage['title']);
                    $this->assertEquals(LanguageFixture::DATA[$readingLanguageReference]['locale'], $readingLanguage['locale']);
                    $this->assertIsString($readingLanguage['image_url']);

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
