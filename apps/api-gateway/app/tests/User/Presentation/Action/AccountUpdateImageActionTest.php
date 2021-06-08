<?php

declare(strict_types=1);

namespace App\Test\User\Presentation\Action;

use App\Common\Infrastructure\Serializer\Normalizer\DataUriNormalizer;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\Request;

/**
 * @internal
 * @coversNothing
 */
final class AccountUpdateImageActionTest extends AbstractUserActionTest
{
    protected function setUp(): void
    {
        self::$httpMethod = Request::METHOD_PATCH;
        self::$httpUri = '/account/update-image';

        parent::setUp();
    }

    public function testSucceeded(): void
    {
        $this->checkSucceeded([
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
        $this->checkFailedValidationFailed(null, [
            'image',
        ]);
    }

    protected function checkProcessHasBeenSucceeded(array $responseData = [], array $options = []): void
    {
        // check http response
        $this->assertEquals([], $responseData);

        // check user has been updated
        $this->assertTrue(self::$currentUser->isImageDefined());

        // check image has been uploaded
        $this->assertTrue((new Filesystem())->exists(self::$container->getParameter('project_image_storage_path').self::$currentUser->getImagePath(true)));

        // check event has been dispatched
        $this->assertCount(1, $this->asyncTransport->get());
        $this->assertEquals(self::$currentUser->getId(), $this->asyncTransport->get()[0]->getMessage()->getId());
        $this->assertEquals(self::$currentUser->getImagePath(true), $this->asyncTransport->get()[0]->getMessage()->getImagePath());
    }

    protected function checkProcessHasBeenStopped(array $responseData = [], array $options = []): void
    {
        // check user has not been updated
        $this->assertFalse(self::$currentUser->isImageDefined());

        // check image has not been uploaded
        $this->assertFalse((new Filesystem())->exists(self::$container->getParameter('project_image_storage_path').self::$currentUser->getImagePath(true)));

        // check event has not been dispatched
        $this->assertCount(0, $this->asyncTransport->get());
    }
}
