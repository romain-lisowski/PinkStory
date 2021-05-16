<?php

declare(strict_types=1);

namespace App\Test\Common\Presentation\Action;

use App\Fixture\User\AccessTokenFixture;
use App\Fixture\User\UserFixture;
use App\User\Domain\Model\AccessToken;
use App\User\Domain\Model\User;
use App\User\Domain\Repository\AccessTokenRepositoryInterface;
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
    protected AccessTokenRepositoryInterface $accessTokenRepository;
    protected UserRepositoryInterface $userRepository;

    protected static User $defaultUser;
    protected static ?User $currentUser = null;

    protected static string $httpMethod = Request::METHOD_GET;
    protected static string $httpUri = '';
    protected static ?string $httpAuthorizationToken = AccessTokenFixture::DATA['access-token-pinkstory']['id'];

    protected function setUp(): void
    {
        parent::setUp();

        $this->client = self::createClient();

        $this->entityManager = self::$container->get('doctrine.orm.entity_manager');

        $this->asyncTransport = self::$container->get('messenger.transport.async');

        $this->accessTokenRepository = self::$container->get('doctrine')->getManager()->getRepository(AccessToken::class);

        $this->userRepository = self::$container->get('doctrine')->getManager()->getRepository(User::class);
        self::$defaultUser = $this->userRepository->findOne(UserFixture::DATA['user-pinkstory']['id']);

        // get fresh current user
        $this->refreshCurrentUser();
    }

    protected function tearDown(): void
    {
        // remove test images
        (new Filesystem())->remove(self::$container->getParameter('project_image_storage_path'));

        // reset access token
        self::$httpAuthorizationToken = AccessTokenFixture::DATA['access-token-pinkstory']['id'];

        parent::tearDown();
    }

    protected function checkSucceeded(array $requestContent = [], array $processOptions = []): void
    {
        $this->client->request(static::$httpMethod, static::$httpUri, [], [], [
            'HTTP_AUTHORIZATION' => null !== static::$httpAuthorizationToken ? 'Bearer '.static::$httpAuthorizationToken : '',
        ], json_encode($requestContent));

        // check http response
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $responseData = json_decode($this->client->getResponse()->getContent(), true);

        // get fresh users
        $this->entityManager->refresh(self::$defaultUser);
        $this->refreshCurrentUser();

        // check process has been succeeded
        $this->checkProcessHasBeenSucceeded($responseData, $processOptions);
    }

    protected function checkFailedUnauthorized(array $requestContent = [], array $processOptions = []): void
    {
        $this->client->request(static::$httpMethod, static::$httpUri, [], [], [], json_encode($requestContent));

        // check http response
        $this->assertEquals(401, $this->client->getResponse()->getStatusCode());
        $responseData = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertEquals('insufficient_authentication_exception', $responseData['exception']['type']);

        // get fresh users
        $this->entityManager->refresh(self::$defaultUser);
        $this->refreshCurrentUser();

        // check process has been stopped
        $this->checkProcessHasBeenStopped($responseData, $processOptions);
    }

    protected function checkFailedAccessDenied(array $requestContent = [], array $processOptions = []): void
    {
        $this->client->request(static::$httpMethod, static::$httpUri, [], [], [
            'HTTP_AUTHORIZATION' => null !== static::$httpAuthorizationToken ? 'Bearer '.static::$httpAuthorizationToken : '',
        ], json_encode($requestContent));

        // check http response
        $this->assertEquals(403, $this->client->getResponse()->getStatusCode());
        $responseData = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertEquals('access_denied_exception', $responseData['exception']['type']);

        // get fresh users
        $this->entityManager->refresh(self::$defaultUser);
        $this->refreshCurrentUser();

        // check process has been stopped
        $this->checkProcessHasBeenStopped($responseData, $processOptions);
    }

    protected function checkFailedMissingMandatory(array $requestContent = [], array $processOptions = []): void
    {
        $this->client->request(static::$httpMethod, static::$httpUri, [], [], [
            'HTTP_AUTHORIZATION' => null !== static::$httpAuthorizationToken ? 'Bearer '.static::$httpAuthorizationToken : '',
        ], json_encode($requestContent));

        // check http response
        $this->assertEquals(400, $this->client->getResponse()->getStatusCode());
        $responseData = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertEquals('request_body_param_missing_mandatory_exception', $responseData['exception']['type']);

        // get fresh users
        $this->entityManager->refresh(self::$defaultUser);
        $this->refreshCurrentUser();

        // check process has been stopped
        $this->checkProcessHasBeenStopped($responseData, $processOptions);
    }

    protected function checkFailedValidationFailed(array $requestContent = [], array $invalidFields = [], array $processOptions = []): void
    {
        $this->client->request(static::$httpMethod, static::$httpUri, [], [], [
            'HTTP_AUTHORIZATION' => null !== static::$httpAuthorizationToken ? 'Bearer '.static::$httpAuthorizationToken : '',
        ], json_encode($requestContent));

        // check http response
        $this->assertEquals(400, $this->client->getResponse()->getStatusCode());
        $responseData = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertEquals('validation_failed_exception', $responseData['exception']['type']);

        foreach ($responseData['exception']['violations'] as $violation) {
            $this->assertTrue(in_array($violation['property_path'], $invalidFields));
        }

        // get fresh users
        $this->entityManager->refresh(self::$defaultUser);
        $this->refreshCurrentUser();

        // check process has been stopped
        $this->checkProcessHasBeenStopped($responseData, $processOptions);
    }

    protected function checkFailedNotFound(array $requestContent = [], array $processOptions = []): void
    {
        $this->client->request(static::$httpMethod, static::$httpUri, [], [], [
            'HTTP_AUTHORIZATION' => null !== static::$httpAuthorizationToken ? 'Bearer '.static::$httpAuthorizationToken : '',
        ], json_encode($requestContent));

        // check http response
        $this->assertEquals(404, $this->client->getResponse()->getStatusCode());
        $responseData = json_decode($this->client->getResponse()->getContent(), true);

        // get fresh users
        $this->entityManager->refresh(self::$defaultUser);
        $this->refreshCurrentUser();

        // check process has been stopped
        $this->checkProcessHasBeenStopped($responseData, $processOptions);
    }

    abstract protected function checkProcessHasBeenSucceeded(array $responseData = [], array $options = []): void;

    abstract protected function checkProcessHasBeenStopped(array $responseData = [], array $options = []): void;

    private function refreshCurrentUser()
    {
        if (null !== self::$httpAuthorizationToken) {
            $accessToken = $this->accessTokenRepository->findOne(self::$httpAuthorizationToken);
            self::$currentUser = $accessToken->getUser();
            $this->entityManager->refresh(self::$currentUser);
        }
    }
}
