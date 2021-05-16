<?php

declare(strict_types=1);

namespace App\Test\User\Presentation\Action;

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
        $this->assertEquals(self::$currentUser->getId(), $responseData['user']['id']);
        $this->assertEquals(self::$currentUser->getGender(), $responseData['user']['gender']);
        $this->assertEquals(self::$currentUser->getName(), $responseData['user']['name']);
        $this->assertEquals(self::$currentUser->getEmail(), $responseData['user']['email']);
        $this->assertFalse($responseData['user']['image_defined']);
        $this->assertEquals(self::$currentUser->getLanguage()->getId(), $responseData['user']['language']['id']);
        $this->assertCount(self::$currentUser->getReadingLanguages()->count(), $responseData['user']['reading_languages']);

        foreach ($responseData['user']['reading_languages'] as $readingLanguage) {
            $exists = false;

            foreach (self::$currentUser->getReadingLanguages() as $userReadingLanguage) {
                if ($userReadingLanguage->getId() === $readingLanguage['id']) {
                    $exists = true;

                    break;
                }
            }

            if (false === $exists) {
                $this->fail('Reading language does not exist.');
            }
        }
    }

    protected function checkProcessHasBeenStopped(array $responseData = [], array $options = []): void
    {
        // nothing to check
    }
}
