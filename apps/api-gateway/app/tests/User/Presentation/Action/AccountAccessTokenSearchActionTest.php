<?php

declare(strict_types=1);

namespace App\Test\User\Presentation\Action;

use App\Fixture\User\AccessTokenFixture;
use Symfony\Component\HttpFoundation\Request;

/**
 * @internal
 * @coversNothing
 */
final class AccountAccessTokenSearchActionTest extends AbstractAccessTokenActionTest
{
    protected function setUp(): void
    {
        parent::setUp();

        self::$httpMethod = Request::METHOD_GET;
        self::$httpUri = '/account/access-token/search';
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
        $this->assertCount(1, $responseData['access-tokens']);

        $this->assertEquals(AccessTokenFixture::DATA['access-token-pinkstory']['id'], $responseData['access-tokens'][0]['id']);
    }

    protected function checkProcessHasBeenStopped(): void
    {
        // nothing to check
    }
}
