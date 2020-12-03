<?php

declare(strict_types=1);

namespace App\User\Action;

use App\Action\AbstractAction;
use App\Form\FormManagerInterface;
use App\Responder\ResponderInterface;
use App\User\Command\UserUpdatePasswordCommand;
use App\User\Command\UserUpdatePasswordCommandFormType;
use App\User\Command\UserUpdatePasswordCommandHandler;
use App\User\Security\UserSecurityInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @IsGranted("ROLE_USER")
 * @Route("/account/update-password", name="account_update_password", methods={"PATCH"})
 */
final class AccountUpdatePasswordAction extends AbstractAction
{
    private FormFactoryInterface $formFactory;
    private FormManagerInterface $formManager;
    private ResponderInterface $responder;
    private UserSecurityInterface $security;
    private UserUpdatePasswordCommandHandler $handler;

    public function __construct(FormFactoryInterface $formFactory, FormManagerInterface $formManager, ResponderInterface $responder, UserSecurityInterface $security, UserUpdatePasswordCommandHandler $handler)
    {
        $this->formFactory = $formFactory;
        $this->formManager = $formManager;
        $this->responder = $responder;
        $this->security = $security;
        $this->handler = $handler;
    }

    public function run(Request $request): Response
    {
        $command = new UserUpdatePasswordCommand();
        $command->id = $this->security->getUser()->getId();

        $form = $this->formFactory->create(UserUpdatePasswordCommandFormType::class, $command);
        $this->formManager->setForm($form)->handleRequest($request);

        $this->handler->setCommand($command)->setCurrentUser($this->security->getUser())->handle();

        return $this->responder->render();
    }
}
