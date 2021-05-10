<?php

declare(strict_types=1);

namespace App\Test\Language\Presentation\Action;

use App\Fixture\Language\LanguageFixture;
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

    protected function checkProcessHasBeenSucceeded(array $responseData = [], array $options = []): void
    {
        $languageFixtures = array_values(LanguageFixture::DATA);

        $this->assertCount(count($languageFixtures), $responseData['languages']);

        foreach ($responseData['languages'] as $i => $data) {
            $this->assertEquals($languageFixtures[$i]['id'], $data['id']);
            $this->assertEquals($languageFixtures[$i]['title'], $data['title']);
            $this->assertEquals($languageFixtures[$i]['locale'], $data['locale']);
        }
    }

    protected function checkProcessHasBeenStopped(): void
    {
        // nothing to check
    }
}
