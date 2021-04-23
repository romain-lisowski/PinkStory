<?php

declare(strict_types=1);

namespace App\Test\User\Presentation\Action;

use App\Fixture\Language\LanguageFixture;
use App\Fixture\User\UserFixture;
use Symfony\Component\HttpFoundation\Request;

/**
 * @internal
 * @coversNothing
 */
final class AccountGetForUpdateActionTest extends AbstractUserActionTest
{
    protected function setUp(): void
    {
        parent::setUp();

        self::$httpMethod = Request::METHOD_GET;
        self::$httpUri = '/account/update';
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
        $this->assertEquals(UserFixture::DATA['user-pinkstory']['id'], $responseData['user']['id']);
        $this->assertEquals(UserFixture::DATA['user-pinkstory']['gender'], $responseData['user']['gender']);
        $this->assertEquals(UserFixture::DATA['user-pinkstory']['name'], $responseData['user']['name']);
        $this->assertEquals(UserFixture::DATA['user-pinkstory']['email'], $responseData['user']['email']);
        $this->assertFalse($responseData['user']['image_defined']);
        $this->assertEquals(LanguageFixture::DATA[UserFixture::DATA['user-pinkstory']['language_reference']]['id'], $responseData['user']['language']['id']);
    }

    protected function checkProcessHasBeenStopped(): void
    {
        // nothing to check
    }
}
