<?php

declare(strict_types=1);

namespace App\Test\Common\Presentation\Action;

use App\Fixture\User\AccessTokenFixture;
use App\Fixture\User\UserFixture;
use App\User\Domain\Model\User;
use App\User\Domain\Repository\UserRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Messenger\Transport\TransportInterface;

/**
 * @internal
 * @coversNothing
 */
abstract class AbstractActionTest extends WebTestCase
{
    protected KernelBrowser $client;
    protected EntityManagerInterface $entityManager;
    protected TransportInterface $asyncTransport;
    protected UserRepositoryInterface $userRepository;

    protected static string $userId = UserFixture::DATA['user-pinkstory']['id'];
    protected static User $user;

    protected static string $httpMethod = Request::METHOD_GET;
    protected static string $httpUri = '';
    protected static ?string $httpAuthorization = 'Bearer '.AccessTokenFixture::DATA['access-token-pinkstory']['id'];

    protected function setUp(): void
    {
        parent::setUp();

        $this->client = self::createClient();

        $this->entityManager = self::$container->get('doctrine.orm.entity_manager');

        $this->asyncTransport = self::$container->get('messenger.transport.async');

        $this->userRepository = self::$container->get('doctrine')->getManager()->getRepository(User::class);
        self::$user = $this->userRepository->findOne(self::$userId);
    }

    protected function tearDown(): void
    {
        // remove test images
        (new Filesystem())->remove(self::$container->getParameter('project_image_storage_path'));

        // reset access token
        self::$httpAuthorization = 'Bearer '.AccessTokenFixture::DATA['access-token-pinkstory']['id'];

        parent::tearDown();
    }

    protected function checkSucceeded(array $requestContent = [], array $processOptions = []): void
    {
        $this->client->request(static::$httpMethod, static::$httpUri, [], [], [
            'HTTP_AUTHORIZATION' => null !== static::$httpAuthorization ? static::$httpAuthorization : '',
        ], json_encode($requestContent));

        // check http response
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $responseData = json_decode($this->client->getResponse()->getContent(), true);

        // get fresh user from database
        $this->entityManager->refresh(self::$user);

        // check process has been succeeded
        $this->checkProcessHasBeenSucceeded($responseData, $processOptions);
    }

    protected function checkFailedUnauthorized(array $requestContent = []): void
    {
        $this->client->request(static::$httpMethod, static::$httpUri, [], [], [], json_encode($requestContent));

        // check http response
        $this->assertEquals(401, $this->client->getResponse()->getStatusCode());
        $responseContent = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertEquals('insufficient_authentication_exception', $responseContent['exception']['type']);

        // get fresh user from database
        $this->entityManager->refresh(self::$user);

        // check process has been stopped
        $this->checkProcessHasBeenStopped();
    }

    protected function checkFailedAccessDenied(array $requestContent = []): void
    {
        $this->client->request(static::$httpMethod, static::$httpUri, [], [], [
            'HTTP_AUTHORIZATION' => null !== static::$httpAuthorization ? static::$httpAuthorization : '',
        ], json_encode($requestContent));

        // check http response
        $this->assertEquals(403, $this->client->getResponse()->getStatusCode());
        $responseContent = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertEquals('access_denied_exception', $responseContent['exception']['type']);

        // get fresh user from database
        $this->entityManager->refresh(self::$user);

        // check process has been stopped
        $this->checkProcessHasBeenStopped();
    }

    protected function checkFailedMissingMandatory(array $requestContent = []): void
    {
        $this->client->request(static::$httpMethod, static::$httpUri, [], [], [
            'HTTP_AUTHORIZATION' => null !== static::$httpAuthorization ? static::$httpAuthorization : '',
        ], json_encode($requestContent));

        // check http response
        $this->assertEquals(400, $this->client->getResponse()->getStatusCode());
        $responseContent = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertEquals('request_body_param_missing_mandatory_exception', $responseContent['exception']['type']);

        // get fresh user from database
        $this->entityManager->refresh(self::$user);

        // check process has been stopped
        $this->checkProcessHasBeenStopped();
    }

    protected function checkFailedValidationFailed(array $requestContent = [], array $invalidFields = []): void
    {
        $this->client->request(static::$httpMethod, static::$httpUri, [], [], [
            'HTTP_AUTHORIZATION' => null !== static::$httpAuthorization ? static::$httpAuthorization : '',
        ], json_encode($requestContent));

        // check http response
        $this->assertEquals(400, $this->client->getResponse()->getStatusCode());
        $responseContent = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertEquals('validation_failed_exception', $responseContent['exception']['type']);

        foreach ($responseContent['exception']['violations'] as $violation) {
            $this->assertTrue(in_array($violation['property_path'], $invalidFields));
        }

        // get fresh user from database
        $this->entityManager->refresh(self::$user);

        // check process has been stopped
        $this->checkProcessHasBeenStopped();
    }

    protected function checkFailedNotFound(array $requestContent = []): void
    {
        $this->client->request(static::$httpMethod, static::$httpUri, [], [], [], json_encode($requestContent));

        // check http response
        $this->assertEquals(404, $this->client->getResponse()->getStatusCode());
        $responseContent = json_decode($this->client->getResponse()->getContent(), true);

        // get fresh user from database
        $this->entityManager->refresh(self::$user);

        // check process has been stopped
        $this->checkProcessHasBeenStopped();
    }

    abstract protected function checkProcessHasBeenSucceeded(array $responseData = [], array $options = []): void;

    abstract protected function checkProcessHasBeenStopped(): void;
}
