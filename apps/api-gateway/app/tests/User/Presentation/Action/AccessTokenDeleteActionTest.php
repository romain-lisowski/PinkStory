<?php

declare(strict_types=1);

namespace App\Test\User\Presentation\Action;

use App\Fixture\User\AccessTokenFixture;
use App\User\Domain\Repository\AccessTokenNoResultException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Uid\Uuid;

/**
 * @internal
 * @coversNothing
 */
final class AccessTokenDeleteActionTest extends AbstractAccessTokenActionTest
{
    protected function setUp(): void
    {
        parent::setUp();

        self::$httpMethod = Request::METHOD_DELETE;
        self::$httpUri = '/access-token/'.AccessTokenFixture::DATA['access-token-john']['id'];
        self::$httpAuthorization = 'Bearer '.AccessTokenFixture::DATA['access-token-john']['id'];

        // get user data
        $this->accessTokens = self::$user->getAccessTokens()->toArray();
    }

    public function testSucceededSameUserLoggedIn(): void
    {
        $this->checkSucceeded();
    }

    public function testSucceededDifferentUserLoggedInButAdmin(): void
    {
        // change user logged in
        self::$httpAuthorization = 'Bearer '.AccessTokenFixture::DATA['access-token-yannis']['id'];

        $this->checkSucceeded();
    }

    public function testSucceededDifferentUserLoggedInButModerator(): void
    {
        // change user logged in
        self::$httpAuthorization = 'Bearer '.AccessTokenFixture::DATA['access-token-leslie']['id'];

        $this->checkSucceeded();
    }

    public function testFailedUnauthorized(): void
    {
        $this->checkFailedUnauthorized();
    }

    public function testFailedDifferentUserLoggedIn(): void
    {
        // change user logged in
        self::$httpAuthorization = 'Bearer '.AccessTokenFixture::DATA['access-token-juliette']['id'];

        $this->checkFailedAccessDenied();
    }

    protected function checkProcessHasBeenSucceeded(array $responseData = [], array $options = []): void
    {
        // check http response
        $this->assertEquals([], $responseData);

        try {
            // check access token has been deleted
            $accessToken = $this->accessTokenRepository->findOne(Uuid::fromString(AccessTokenFixture::DATA['access-token-john']['id'])->toRfc4122());

            $this->fail();
        } catch (AccessTokenNoResultException $e) {
            $this->assertTrue(true);

            // check event has been dispatched
            $this->assertCount(1, $this->asyncTransport->get());
            $this->assertEquals(AccessTokenFixture::DATA['access-token-john']['id'], $this->asyncTransport->get()[0]->getMessage()->getId());
        }
    }

    protected function checkProcessHasBeenStopped(): void
    {
        try {
            // check access token has not been deleted
            $accessToken = $this->accessTokenRepository->findOne(Uuid::fromString(AccessTokenFixture::DATA['access-token-john']['id'])->toRfc4122());

            $this->assertTrue(true);

            // check event has not been dispatched
            $this->assertCount(0, $this->asyncTransport->get());
        } catch (AccessTokenNoResultException $e) {
            $this->fail();
        }
    }
}
