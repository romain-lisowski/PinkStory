<?php

declare(strict_types=1);

namespace App\Test\User\Presentation\Action;

use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\Request;

/**
 * @internal
 * @coversNothing
 */
final class AccountDeleteImageActionTest extends AbstractUserActionTest
{
    protected static string $httpMethod = Request::METHOD_DELETE;
    protected static string $httpUri = '/account/delete-image';

    protected function setUp(): void
    {
        parent::setUp();

        // init user image
        self::$user->setImageDefined(true);
        $this->userRepository->flush();
        (new Filesystem())->copy(__DIR__.'/../../../image/test.jpg', self::$container->getParameter('project_image_storage_path').self::$user->getImagePath(true));
    }

    public function testSuccess(): void
    {
        $this->checkSuccess();
    }

    public function testFailedUnauthorized(): void
    {
        $this->checkFailedUnauthorized();
    }

    protected function checkProcessHasBeenSucceeded(array $responseData = [], array $options = []): void
    {
        // check http response
        $this->assertEquals([], $responseData);

        // check user has been updated
        $this->assertFalse(self::$user->isImageDefined());

        // check image has been deleted
        $this->assertFalse((new Filesystem())->exists(self::$container->getParameter('project_image_storage_path').self::$user->getImagePath(true)));

        // check event has been dispatched
        $this->assertCount(1, $this->asyncTransport->get());
        $this->assertEquals(self::$user->getId(), $this->asyncTransport->get()[0]->getMessage()->getId());
    }

    protected function checkProcessHasBeenStopped(): void
    {
        // check user has not been updated
        $this->assertTrue(self::$user->isImageDefined());

        // check image has not been deleted
        $this->assertTrue((new Filesystem())->exists(self::$container->getParameter('project_image_storage_path').self::$user->getImagePath(true)));

        // check event has not been dispatched
        $this->assertCount(0, $this->asyncTransport->get());
    }
}
