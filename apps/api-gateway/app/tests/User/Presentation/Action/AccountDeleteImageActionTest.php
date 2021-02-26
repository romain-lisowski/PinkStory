<?php

declare(strict_types=1);

namespace App\Test\User\Presentation\Action;

use App\User\Domain\Event\UserDeletedImageEvent;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\Request;

/**
 * @internal
 * @coversNothing
 */
final class AccountDeleteImageActionTest extends AbastractUserActionTest
{
    protected const HTTP_METHOD = Request::METHOD_DELETE;
    protected const HTTP_URI = '/account/delete-image';

    protected function setUp(): void
    {
        parent::setUp();

        // init user image
        $user = $this->userRepository->findOne(self::PINKSTORY_USER_DATA['id']);
        $user->setImageDefined(true);
        $this->userRepository->flush();
        (new Filesystem())->copy(__DIR__.'/../../../image/test.jpg', self::$container->getParameter('project_image_storage_path').$user->getImagePath(true));
    }

    public function testSuccess(): void
    {
        $this->checkSuccess();
    }

    public function testFailedUnauthorized(): void
    {
        $this->checkFailedUnauthorized();
    }

    protected function checkProcessHasBeenSucceeded(array $options = []): void
    {
        // get fresh user from database
        $user = $this->userRepository->findOne(self::PINKSTORY_USER_DATA['id']);
        $this->entityManager->refresh($user);

        // check user has been updated
        $this->assertFalse($user->isImageDefined());

        // check image has been deleted
        $this->assertFalse((new Filesystem())->exists(self::$container->getParameter('project_image_storage_path').$user->getImagePath(true)));

        // check event has been dispatched
        $this->assertCount(1, $this->asyncTransport->get());
        $this->assertInstanceOf(UserDeletedImageEvent::class, $this->asyncTransport->get()[0]->getMessage());
        $this->assertEquals($user->getId(), $this->asyncTransport->get()[0]->getMessage()->getId());
    }

    protected function checkProcessHasBeenStopped(): void
    {
        // get fresh user from database
        $user = $this->userRepository->findOne(self::PINKSTORY_USER_DATA['id']);
        $this->entityManager->refresh($user);

        // check user has not been updated
        $this->assertTrue($user->isImageDefined());

        // check image has not been deleted
        $this->assertTrue((new Filesystem())->exists(self::$container->getParameter('project_image_storage_path').$user->getImagePath(true)));

        // check event has not been dispatched
        $this->assertCount(0, $this->asyncTransport->get());
    }
}
