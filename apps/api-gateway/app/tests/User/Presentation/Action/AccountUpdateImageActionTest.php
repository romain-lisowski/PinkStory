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
    private const USER_DATA = [
        'access_token' => 'f478da1e-f5a8-4c28-a5e2-77abeb7f1cdf',
        'email' => 'hello@pinkstory.io',
    ];

    public function testSuccess(): void
    {
        $this->client->request('PATCH', '/account/update-image', [], [], [
            'HTTP_AUTHORIZATION' => 'Bearer '.self::USER_DATA['access_token'],
        ], json_encode([
            'image' => (new DataUriNormalizer())->normalize(new File(__DIR__.'/../../../image/test.jpg'), ''),
        ]));

        // check http response
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertEquals(json_encode([]), $this->client->getResponse()->getContent());

        $user = $this->userRepository->findOneByEmail(self::USER_DATA['email']);

        // check image has been uploaded
        $this->assertTrue($user->isImageDefined());
        $this->assertTrue((new Filesystem())->exists(self::$container->getParameter('project_image_storage_path').$user->getImagePath()));
    }

    public function testFailedUnauthorized(): void
    {
        $this->client->request('PATCH', '/account/update-image', [], [], [], json_encode([
            'image' => (new DataUriNormalizer())->normalize(new File(__DIR__.'/../../../image/test.jpg'), ''),
        ]));

        // check http response
        $this->assertEquals(401, $this->client->getResponse()->getStatusCode());
        // TODO: check response body content

        $user = $this->userRepository->findOneByEmail(self::USER_DATA['email']);

        // check image has been uploaded
        $this->assertFalse($user->isImageDefined());
        $this->assertFalse((new Filesystem())->exists(self::$container->getParameter('project_image_storage_path').$user->getImagePath()));
    }
}
