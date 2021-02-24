<?php

declare(strict_types=1);

namespace App\Test\User\Presentation\Action;

use App\Common\Infrastructure\Serializer\Normalizer\DataUriNormalizer;
use App\User\Domain\Event\UserUpdatedImageEvent;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\File;

/**
 * @internal
 * @coversNothing
 */
final class AccountUpdateImageActionTest extends AbastractUserActionTest
{
    public function testSuccess(): void
    {
        $this->client->request('PATCH', '/account/update-image', [], [], [
            'HTTP_AUTHORIZATION' => 'Bearer '.self::PINKSTORY_USER_DATA['access_token'],
        ], json_encode([
            'image' => (new DataUriNormalizer())->normalize(new File(__DIR__.'/../../../image/test.jpg'), ''),
        ]));

        // check http response
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $responseContent = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertEquals([], $responseContent);

        $user = $this->userRepository->findOne(self::PINKSTORY_USER_DATA['id']);

        // check image has been uploaded
        $this->assertTrue($user->isImageDefined());
        $this->assertTrue((new Filesystem())->exists(self::$container->getParameter('project_image_storage_path').$user->getImagePath(true)));

        // check event has been dispatched
        $this->assertCount(1, $this->asyncTransport->get());
        $this->assertInstanceOf(UserUpdatedImageEvent::class, $this->asyncTransport->get()[0]->getMessage());
        $this->assertEquals(self::PINKSTORY_USER_DATA['id'], $this->asyncTransport->get()[0]->getMessage()->getId());
        $this->assertEquals($user->getImagePath(true), $this->asyncTransport->get()[0]->getMessage()->getImagePath());
    }

    public function testFailedUnauthorized(): void
    {
        $this->client->request('PATCH', '/account/update-image', [], [], [], json_encode([
            'image' => (new DataUriNormalizer())->normalize(new File(__DIR__.'/../../../image/test.jpg'), ''),
        ]));

        // check http response
        $this->assertEquals(401, $this->client->getResponse()->getStatusCode());
        $responseContent = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertEquals('insufficient_authentication_exception', $responseContent['exception']['type']);

        $user = $this->userRepository->findOne(self::PINKSTORY_USER_DATA['id']);

        // check image has not been uploaded
        $this->assertFalse($user->isImageDefined());
        $this->assertFalse((new Filesystem())->exists(self::$container->getParameter('project_image_storage_path').$user->getImagePath(true)));

        // check event has not been dispatched
        $this->assertCount(0, $this->asyncTransport->get());
    }
}
