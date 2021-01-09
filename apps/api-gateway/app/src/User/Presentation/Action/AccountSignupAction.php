<?php

declare(strict_types=1);

namespace App\User\Presentation\Action;

use App\Common\Presentation\Http\ResponderInterface;
use App\User\Domain\Command\UserCreateCommand;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/account/signup", name="account_signup", methods={"POST"})
 */
final class AccountSignupAction
{
    private ResponderInterface $responder;

    public function __construct(ResponderInterface $responder)
    {
        $this->responder = $responder;
    }

    public function __invoke(UserCreateCommand $command): Response
    {
        return $this->responder->render([
            'command' => $command,
        ]);
    }
}
