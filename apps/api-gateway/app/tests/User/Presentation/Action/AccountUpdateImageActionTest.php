<?php

declare(strict_types=1);

namespace App\Test\User\Presentation\Action;

use App\Common\Infrastructure\Serializer\Normalizer\DataUriNormalizer;
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
        $this->assertEquals(json_decode($this->client->getResponse()->getContent(), true), []);

        $user = $this->userRepository->findOneByEmail(self::PINKSTORY_USER_DATA['email']);

        // check image has been uploaded
        $this->assertTrue($user->isImageDefined());
        $this->assertTrue((new Filesystem())->exists(self::$container->getParameter('project_image_storage_path').$user->getImagePath(true)));
    }

    public function testFailedUnauthorized(): void
    {
        $this->client->request('PATCH', '/account/update-image', [], [], [], json_encode([
            'image' => (new DataUriNormalizer())->normalize(new File(__DIR__.'/../../../image/test.jpg'), ''),
        ]));

        // check http response
        $this->assertEquals(401, $this->client->getResponse()->getStatusCode());
        $responseContent = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertEquals($responseContent['exception']['type'], 'insufficient_authentication_exception');

        $user = $this->userRepository->findOneByEmail(self::PINKSTORY_USER_DATA['email']);

        // check image has been uploaded
        $this->assertFalse($user->isImageDefined());
        $this->assertFalse((new Filesystem())->exists(self::$container->getParameter('project_image_storage_path').$user->getImagePath(true)));
    }
}
