<?php

declare(strict_types=1);

namespace App\Test\User\Presentation\Action;

use App\Fixture\User\UserFixture;
use App\User\Domain\Repository\AccessTokenNoResultException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Uid\Uuid;

/**
 * @internal
 * @coversNothing
 */
final class AccessTokenCreateActionTest extends AbstractAccessTokenActionTest
{
    private array $accessTokens;

    protected function setUp(): void
    {
        parent::setUp();

        self::$httpMethod = Request::METHOD_POST;
        self::$httpUri = '/access-token';
        self::$httpAuthorization = null;

        // get user data
        $this->accessTokens = self::$user->getAccessTokens()->toArray();
    }

    public function testSucceeded(): void
    {
        $this->checkSucceeded([
            'email' => self::$user->getEmail(),
            'password' => UserFixture::DATA['user-pinkstory']['password'],
        ]);
    }

    public function testFailedMissingEmail(): void
    {
        $this->checkFailedMissingMandatory([
            'password' => UserFixture::DATA['user-pinkstory']['password'],
        ]);
    }

    public function testFailedWrongFormatEmail(): void
    {
        $this->checkFailedValidationFailed([
            'email' => 'email',
            'password' => UserFixture::DATA['user-pinkstory']['password'],
        ], [
            'email',
        ]);
    }

    public function testFailedNonExistentEmail(): void
    {
        $this->checkFailedValidationFailed([
            'email' => 'email@email.em',
            'password' => UserFixture::DATA['user-pinkstory']['password'],
        ], [
            'email',
        ]);
    }

    public function testFailedMissingPassword(): void
    {
        $this->checkFailedMissingMandatory([
            'email' => self::$user->getEmail(),
        ]);
    }

    public function testFailedWrongPassword(): void
    {
        $this->checkFailedValidationFailed([
            'email' => self::$user->getEmail(),
            'password' => 'password',
        ], [
            'email', // no password error, don't specify too much : only "bad credentials" error
        ]);
    }

    protected function checkProcessHasBeenSucceeded(array $responseData = [], array $options = []): void
    {
        // check http response
        $this->assertInstanceOf(Uuid::class, Uuid::fromString($responseData['access_token']['id']));

        try {
            // check access token has been created
            $accessToken = $this->accessTokenRepository->findOne(Uuid::fromString($responseData['access_token']['id'])->toRfc4122());

            // check event has been dispatched
            $this->assertCount(1, $this->asyncTransport->get());
            $this->assertEquals($accessToken->getId(), $this->asyncTransport->get()[0]->getMessage()->getId());
            $this->assertEquals(self::$user->getId(), $this->asyncTransport->get()[0]->getMessage()->getUserId());
        } catch (AccessTokenNoResultException $e) {
            $this->fail();
        }
    }

    protected function checkProcessHasBeenStopped(): void
    {
        // check no access token has been created
        $this->entityManager->refresh(self::$user);
        $this->assertCount(count($this->accessTokens), self::$user->getAccessTokens());

        // check event has not been dispatched
        $this->assertCount(0, $this->asyncTransport->get());
    }
}
