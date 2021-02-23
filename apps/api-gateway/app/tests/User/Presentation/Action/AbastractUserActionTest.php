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
    protected const PINKSTORY_USER_DATA = [
        'access_token' => 'f478da1e-f5a8-4c28-a5e2-77abeb7f1cdf',
        'email' => 'hello@pinkstory.io',
    ];

    protected UserRepositoryInterface $userRepository;

    protected function setUp(): void
    {
        parent::setUp();

        $this->userRepository = self::$container->get('doctrine')->getManager()->getRepository(User::class);
    }
}
