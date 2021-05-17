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
            'editable' => true,
            'language_editable' => false,
        ]);
    }

    public function testSucceededAdmin(): void
    {
        // change user logged in
        self::$httpAuthorizationToken = AccessTokenFixture::DATA['access-token-yannis']['id'];

        $this->checkSucceeded([], [
            'editable' => true,
            'language_editable' => true,
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
        $this->assertFalse($responseData['user']['image_defined']);
        $this->assertEquals($options['editable'], $responseData['user']['editable']);
        $this->assertEquals(self::$currentUser->getLanguage()->getId(), $responseData['user']['language']['id']);
        $this->assertEquals($options['language_editable'], $responseData['user']['language']['editable']);

        $this->assertCount(self::$currentUser->getReadingLanguages()->count(), $responseData['user']['reading_languages']);

        foreach (self::$currentUser->getReadingLanguages() as $userReadingLanguage) {
            $exists = false;

            foreach ($responseData['user']['reading_languages'] as $readingLanguage) {
                if ($userReadingLanguage->getId() === $readingLanguage['id']) {
                    $this->assertEquals($options['language_editable'], $readingLanguage['editable']);
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
