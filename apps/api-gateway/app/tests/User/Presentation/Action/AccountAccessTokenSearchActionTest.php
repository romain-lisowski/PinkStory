<?php

declare(strict_types=1);

namespace App\Test\User\Presentation\Action;

use Symfony\Component\HttpFoundation\Request;

/**
 * @internal
 * @coversNothing
 */
final class AccountAccessTokenSearchActionTest extends AbstractAccessTokenActionTest
{
    protected function setUp(): void
    {
        self::$httpMethod = Request::METHOD_GET;
        self::$httpUri = '/account/access-token/search';

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
        $this->assertCount(self::$currentUser->getAccessTokens()->count(), $responseData['access_tokens']);

        foreach (self::$currentUser->getAccessTokens() as $accessTokenFixture) {
            $exists = false;

            foreach ($responseData['access_tokens'] as $accessToken) {
                if ($accessTokenFixture->getId() === $accessToken['id']) {
                    $this->assertTrue($accessToken['editable']);
                    $this->assertEquals(self::$currentUser->getId(), $accessToken['user']['id']);
                    $this->assertTrue($accessToken['user']['editable']);

                    $exists = true;
                }
            }

            if (false === $exists) {
                $this->fail('Access token ['.$accessTokenFixture->getId().'] does not exist.');
            }
        }
    }

    protected function checkProcessHasBeenStopped(array $responseData = [], array $options = []): void
    {
        // nothing to check
    }
}
