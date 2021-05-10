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
    protected function setUp(): void
    {
        self::$httpMethod = Request::METHOD_DELETE;
        self::$httpUri = '/account/delete-image';

        parent::setUp();

        // init user image
        self::$currentUser->setImageDefined(true);
        $this->userRepository->flush();
        (new Filesystem())->copy(__DIR__.'/../../../image/test.jpg', self::$container->getParameter('project_image_storage_path').self::$currentUser->getImagePath(true));
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
        // check http response
        $this->assertEquals([], $responseData);

        // check user has been updated
        $this->assertFalse(self::$currentUser->isImageDefined());

        // check image has been deleted
        $this->assertFalse((new Filesystem())->exists(self::$container->getParameter('project_image_storage_path').self::$currentUser->getImagePath(true)));

        // check event has been dispatched
        $this->assertCount(1, $this->asyncTransport->get());
        $this->assertEquals(self::$currentUser->getId(), $this->asyncTransport->get()[0]->getMessage()->getId());
    }

    protected function checkProcessHasBeenStopped(): void
    {
        // check user has not been updated
        $this->assertTrue(self::$currentUser->isImageDefined());

        // check image has not been deleted
        $this->assertTrue((new Filesystem())->exists(self::$container->getParameter('project_image_storage_path').self::$currentUser->getImagePath(true)));

        // check event has not been dispatched
        $this->assertCount(0, $this->asyncTransport->get());
    }
}
