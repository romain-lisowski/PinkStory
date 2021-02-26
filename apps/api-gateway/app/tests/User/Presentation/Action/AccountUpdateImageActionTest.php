<?php

declare(strict_types=1);

namespace App\Test\User\Presentation\Action;

use App\Common\Infrastructure\Serializer\Normalizer\DataUriNormalizer;
use App\User\Domain\Event\UserUpdatedImageEvent;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\Request;

/**
 * @internal
 * @coversNothing
 */
final class AccountUpdateImageActionTest extends AbastractUserActionTest
{
    protected static string $httpMethod = Request::METHOD_PATCH;
    protected static string $httpUri = '/account/update-image';

    public function testSuccess(): void
    {
        $this->checkSuccess([
            'image' => (new DataUriNormalizer())->normalize(new File(__DIR__.'/../../../image/test.jpg'), ''),
        ]);
    }

    public function testFailedUnauthorized(): void
    {
        $this->checkFailedUnauthorized([
            'image' => (new DataUriNormalizer())->normalize(new File(__DIR__.'/../../../image/test.jpg'), ''),
        ]);
    }

    public function testFailedMissingImage(): void
    {
        $this->checkFailedMissingMandatory();
    }

    protected function checkProcessHasBeenSucceeded(array $options = []): void
    {
        // get fresh user from database
        $user = $this->userRepository->findOne(self::$pinkstoryUserData['id']);
        $this->entityManager->refresh($user);

        // check user has been updated
        $this->assertTrue($user->isImageDefined());

        // check image has been uploaded
        $this->assertTrue((new Filesystem())->exists(self::$container->getParameter('project_image_storage_path').$user->getImagePath(true)));

        // check event has been dispatched
        $this->assertCount(1, $this->asyncTransport->get());
        $this->assertInstanceOf(UserUpdatedImageEvent::class, $this->asyncTransport->get()[0]->getMessage());
        $this->assertEquals($user->getId(), $this->asyncTransport->get()[0]->getMessage()->getId());
        $this->assertEquals($user->getImagePath(true), $this->asyncTransport->get()[0]->getMessage()->getImagePath());
    }

    protected function checkProcessHasBeenStopped(): void
    {
        // get fresh user from database
        $user = $this->userRepository->findOne(self::$pinkstoryUserData['id']);
        $this->entityManager->refresh($user);

        // check user has not been updated
        $this->assertFalse($user->isImageDefined());

        // check image has not been uploaded
        $this->assertFalse((new Filesystem())->exists(self::$container->getParameter('project_image_storage_path').$user->getImagePath(true)));

        // check event has not been dispatched
        $this->assertCount(0, $this->asyncTransport->get());
    }
}
