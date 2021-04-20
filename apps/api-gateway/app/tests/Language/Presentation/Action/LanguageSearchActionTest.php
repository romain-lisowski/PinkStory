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
    protected static string $httpMethod = Request::METHOD_GET;
    protected static string $httpUri = '/language/search';

    protected function setUp(): void
    {
        parent::setUp();

        self::$httpAuthorization = null;
    }

    public function testSuccess(): void
    {
        $this->checkSuccess();
    }

    protected function checkProcessHasBeenSucceeded(array $responseData = [], array $options = []): void
    {
        $this->assertCount(4, $responseData['languages']);

        $languageFixtures = array_values(LanguageFixture::DATA);

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
