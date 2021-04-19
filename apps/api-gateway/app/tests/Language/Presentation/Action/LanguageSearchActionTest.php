<?php

declare(strict_types=1);

namespace App\Test\Language\Presentation\Action;

use App\Fixture\Language\LanguageFixture;
use Symfony\Component\HttpFoundation\Request;

/**
 * @internal
 * @coversNothing
 */
final class LanguageSearchActionTest extends AbastractLanguageActionTest
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

        foreach ($responseData['languages'] as $i => $data) {
            $this->assertEquals(LanguageFixture::DATA[$i]['id'], $data['id']);
            $this->assertEquals(LanguageFixture::DATA[$i]['title'], $data['title']);
            $this->assertEquals(LanguageFixture::DATA[$i]['locale'], $data['locale']);
        }
    }

    protected function checkProcessHasBeenStopped(): void
    {
        // nothing to check
    }
}
