<?php

declare(strict_types=1);

namespace App\Test\Common\Presentation\Action;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Messenger\Transport\TransportInterface;

/**
 * @internal
 * @coversNothing
 */
abstract class AbastractActionTest extends WebTestCase
{
    protected KernelBrowser $client;
    protected EntityManagerInterface $entityManager;
    protected TransportInterface $asyncTransport;

    protected function setUp(): void
    {
        parent::setUp();

        $this->client = static::createClient();

        $this->entityManager = self::$container->get('doctrine.orm.entity_manager');

        $this->asyncTransport = self::$container->get('messenger.transport.async');
    }

    protected function tearDown(): void
    {
        // remove test images
        (new Filesystem())->remove(self::$container->getParameter('project_image_storage_path'));

        parent::tearDown();
    }
}
