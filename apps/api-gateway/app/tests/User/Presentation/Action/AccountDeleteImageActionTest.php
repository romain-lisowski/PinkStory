<?php

declare(strict_types=1);

namespace App\Test\User\Presentation\Action;

use App\User\Domain\Event\UserDeletedImageEvent;
use Symfony\Component\Filesystem\Filesystem;

/**
 * @internal
 * @coversNothing
 */
final class AccountDeleteImageActionTest extends AbastractUserActionTest
{
    protected function setUp(): void
    {
        parent::setUp();

        // init user image
        $user = $this->userRepository->findOneByEmail(self::PINKSTORY_USER_DATA['email']);
        $user->setImageDefined(true);
        $this->userRepository->flush();
        (new Filesystem())->copy(__DIR__.'/../../../image/test.jpg', self::$container->getParameter('project_image_storage_path').$user->getImagePath(true));
    }

    public function testSuccess(): void
    {
        $this->client->request('DELETE', '/account/delete-image', [], [], [
            'HTTP_AUTHORIZATION' => 'Bearer '.self::PINKSTORY_USER_DATA['access_token'],
        ]);

        // check http response
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $responseContent = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertEquals([], $responseContent);

        $user = $this->userRepository->findOne(self::PINKSTORY_USER_DATA['id']);

        // check image has been deleted
        $this->assertFalse($user->isImageDefined());
        $this->assertFalse((new Filesystem())->exists(self::$container->getParameter('project_image_storage_path').$user->getImagePath(true)));

        // check event has been dispatched
        $this->assertCount(1, $this->asyncTransport->get());
        $this->assertInstanceOf(UserDeletedImageEvent::class, $this->asyncTransport->get()[0]->getMessage());
        $this->assertEquals(self::PINKSTORY_USER_DATA['id'], $this->asyncTransport->get()[0]->getMessage()->getId());
    }

    public function testFailedUnauthorized(): void
    {
        $this->client->request('DELETE', '/account/delete-image');

        // check http response
        $this->assertEquals(401, $this->client->getResponse()->getStatusCode());
        $responseContent = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertEquals('insufficient_authentication_exception', $responseContent['exception']['type']);

        $user = $this->userRepository->findOne(self::PINKSTORY_USER_DATA['id']);

        // check image has not been deleted
        $this->assertTrue($user->isImageDefined());
        $this->assertTrue((new Filesystem())->exists(self::$container->getParameter('project_image_storage_path').$user->getImagePath(true)));

        // check event has not been dispatched
        $this->assertCount(0, $this->asyncTransport->get());
    }
}
