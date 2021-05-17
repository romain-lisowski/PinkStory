<?php

declare(strict_types=1);

namespace App\Test\User\Presentation\Action;

use App\Fixture\User\AccessTokenFixture;
use Symfony\Component\HttpFoundation\Request;

/**
 * @internal
 * @coversNothing
 */
final class AccountGetForUpdateActionTest extends AbstractUserActionTest
{
    protected function setUp(): void
    {
        self::$httpMethod = Request::METHOD_GET;
        self::$httpUri = '/account/update';
        self::$httpAuthorizationToken = AccessTokenFixture::DATA['access-token-john']['id'];

        parent::setUp();
    }

    public function testSucceeded(): void
    {
        $this->checkSucceeded([], [
            'image_defined' => false,
            'editable' => true,
        ]);
    }

    public function testSucceededAdmin(): void
    {
        // change user logged in
        self::$httpAuthorizationToken = AccessTokenFixture::DATA['access-token-yannis']['id'];

        $this->checkSucceeded([], [
            'image_defined' => false,
            'editable' => true,
        ]);
    }

    public function testFailedUnauthorized(): void
    {
        $this->checkFailedUnauthorized();
    }

    protected function checkProcessHasBeenSucceeded(array $responseData = [], array $options = []): void
    {
        $this->assertEquals(self::$currentUser->getId(), $responseData['user']['id']);
        $this->assertEquals(self::$currentUser->getGender(), $responseData['user']['gender']);
        $this->assertEquals(self::$currentUser->getName(), $responseData['user']['name']);
        $this->assertEquals(self::$currentUser->getEmail(), $responseData['user']['email']);
        $this->assertEquals($options['image_defined'], is_string($responseData['user']['image_url']));
        $this->assertEquals($options['editable'], $responseData['user']['editable']);
        $this->assertEquals(self::$currentUser->getLanguage()->getId(), $responseData['user']['language']['id']);

        $this->assertCount(self::$currentUser->getReadingLanguages()->count(), $responseData['user']['reading_languages']);

        foreach (self::$currentUser->getReadingLanguages() as $userReadingLanguage) {
            $exists = false;

            foreach ($responseData['user']['reading_languages'] as $readingLanguage) {
                if ($userReadingLanguage->getId() === $readingLanguage['id']) {
                    $exists = true;

                    break;
                }
            }

            if (false === $exists) {
                $this->fail('Reading language ['.$userReadingLanguage->getId().'] does not exist.');
            }
        }
    }

    protected function checkProcessHasBeenStopped(array $responseData = [], array $options = []): void
    {
        // nothing to check
    }
}
