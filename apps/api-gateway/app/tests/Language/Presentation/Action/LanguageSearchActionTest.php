<?php

declare(strict_types=1);

namespace App\Test\Language\Presentation\Action;

use App\Fixture\Language\LanguageFixture;
use App\Fixture\User\AccessTokenFixture;
use Symfony\Component\HttpFoundation\Request;

/**
 * @internal
 * @coversNothing
 */
final class LanguageSearchActionTest extends AbstractLanguageActionTest
{
    protected function setUp(): void
    {
        self::$httpMethod = Request::METHOD_GET;
        self::$httpUri = '/language/search';
        self::$httpAuthorizationToken = null;

        parent::setUp();
    }

    public function testSucceeded(): void
    {
        $this->checkSucceeded();
    }

    public function testSucceededLogginAdmin(): void
    {
        // change user logged in
        self::$httpAuthorizationToken = AccessTokenFixture::DATA['access-token-yannis']['id'];

        $this->checkSucceeded();
    }

    public function testSucceededLogginModerator(): void
    {
        // change user logged in
        self::$httpAuthorizationToken = AccessTokenFixture::DATA['access-token-leslie']['id'];

        $this->checkSucceeded();
    }

    public function testSucceededLogginUser(): void
    {
        // change user logged in
        self::$httpAuthorizationToken = AccessTokenFixture::DATA['access-token-john']['id'];

        $this->checkSucceeded();
    }

    protected function checkProcessHasBeenSucceeded(array $responseData = [], array $options = []): void
    {
        $languageFixtures = array_values(LanguageFixture::DATA);

        $this->assertCount(count($languageFixtures), $responseData['languages']);

        foreach ($responseData['languages'] as $i => $data) {
            $this->assertEquals($languageFixtures[$i]['id'], $data['id']);
            $this->assertEquals($languageFixtures[$i]['title'], $data['title']);
            $this->assertEquals($languageFixtures[$i]['locale'], $data['locale']);
            $this->assertIsString($data['image_url']);
        }
    }

    protected function checkProcessHasBeenStopped(array $responseData = [], array $options = []): void
    {
        // nothing to check
    }
}
