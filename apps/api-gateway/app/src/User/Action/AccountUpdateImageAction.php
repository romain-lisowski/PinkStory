<?php

declare(strict_types=1);

namespace App\User\Action;

use App\Action\AbstractAction;
use App\Form\FormManagerInterface;
use App\Responder\ResponderInterface;
use App\User\Command\UserUpdateImageCommand;
use App\User\Command\UserUpdateImageCommandFormType;
use App\User\Command\UserUpdateImageCommandHandler;
use App\User\Security\UserSecurityInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @IsGranted("ROLE_USER")
 * @Route("/account/update-image", name="account_update_image", methods={"PATCH"})
 */
final class AccountUpdateImageAction extends AbstractAction
{
    private FormFactoryInterface $formFactory;
    private FormManagerInterface $formManager;
    private ResponderInterface $responder;
    private UserSecurityInterface $security;
    private UserUpdateImageCommandHandler $handler;

    public function __construct(FormFactoryInterface $formFactory, FormManagerInterface $formManager, ResponderInterface $responder, UserSecurityInterface $security, UserUpdateImageCommandHandler $handler)
    {
        $this->formFactory = $formFactory;
        $this->formManager = $formManager;
        $this->responder = $responder;
        $this->security = $security;
        $this->handler = $handler;
    }

    public function run(Request $request): Response
    {
        $command = new UserUpdateImageCommand();
        $command->id = $this->security->getUser()->getId();

        $form = $this->formFactory->create(UserUpdateImageCommandFormType::class, $command);
        $this->formManager->setForm($form)->handleRequest($request);

        $this->handler->setCommand($command)->setCurrentUser($this->security->getUser())->handle();

        return $this->responder->render();
    }
}
