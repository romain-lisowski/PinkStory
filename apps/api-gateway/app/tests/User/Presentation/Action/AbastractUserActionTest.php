<?php

declare(strict_types=1);

namespace App\Test\User\Presentation\Action;

use App\Test\Common\Presentation\Action\AbastractActionTest;
use App\User\Domain\Model\User;
use App\User\Domain\Repository\UserRepositoryInterface;

/**
 * @internal
 * @coversNothing
 */
abstract class AbastractUserActionTest extends AbastractActionTest
{
    protected UserRepositoryInterface $userRepository;

    protected function setUp(): void
    {
        parent::setUp();

        $this->userRepository = self::$container->get('doctrine')->getManager()->getRepository(User::class);
    }
}
