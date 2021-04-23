<?php

declare(strict_types=1);

namespace App\Test\User\Presentation\Action;

use App\Common\Domain\Translation\TranslatorInterface;
use App\User\Domain\Model\UserGender;
use Symfony\Component\HttpFoundation\Request;

/**
 * @internal
 * @coversNothing
 */
final class UserGenderSearchActionTest extends AbstractUserActionTest
{
    protected function setUp(): void
    {
        parent::setUp();

        self::$httpMethod = Request::METHOD_GET;
        self::$httpUri = '/user-gender/search';
        self::$httpAuthorization = null;
    }

    public function testSucceeded(): void
    {
        $this->checkSucceeded();
    }

    protected function checkProcessHasBeenSucceeded(array $responseData = [], array $options = []): void
    {
        $this->assertCount(4, $responseData['user-genders']);

        foreach ($responseData['user-genders'] as $key => $value) {
            $this->assertEquals(UserGender::getReadingChoice($key, self::$container->get(TranslatorInterface::class)), $value);
        }
    }

    protected function checkProcessHasBeenStopped(): void
    {
        // nothing to check
    }
}
