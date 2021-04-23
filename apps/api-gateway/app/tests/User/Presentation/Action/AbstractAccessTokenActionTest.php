<?php

declare(strict_types=1);

namespace App\Test\User\Presentation\Action;

use App\Test\Common\Presentation\Action\AbstractActionTest;
use App\User\Domain\Model\AccessToken;
use App\User\Domain\Repository\AccessTokenRepositoryInterface;

/**
 * @internal
 * @coversNothing
 */
abstract class AbstractAccessTokenActionTest extends AbstractActionTest
{
    protected AccessTokenRepositoryInterface $accessTokenRepository;

    protected function setUp(): void
    {
        parent::setUp();

        $this->accessTokenRepository = self::$container->get('doctrine')->getManager()->getRepository(AccessToken::class);
    }
}
