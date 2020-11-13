<?php

declare(strict_types=1);

namespace App\Mercure\Action;

use App\Exception\InvalidFormException;
use App\Exception\NotSubmittedFormException;
use App\Mercure\Command\MercureCommand;
use App\Mercure\Command\MercureCommandHandler;
use App\Responder\ResponderInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Throwable;

/**
 * @Route("/mercure", name="mercure", methods={"POST"})
 */
final class MercureAction
{
    private FormFactoryInterface $formFactory;
    private ResponderInterface $responder;
    private MercureCommandHandler $handler;

    public function __construct(FormFactoryInterface $formFactory, ResponderInterface $responder, MercureCommandHandler $handler)
    {
        $this->formFactory = $formFactory;
        $this->responder = $responder;
        $this->handler = $handler;
    }

    public function __invoke(Request $request): Response
    {
        try {
            $command = new MercureCommand();

            $form = $this->formFactory->create(MercureCommandFormType::class, $command);

            $form->handleRequest($request);

            if (false === $form->isSubmitted()) {
                throw new NotSubmittedFormException();
            }

            if (false === $form->isValid()) {
                throw new InvalidFormException($form->getErrors(true));
            }

            $this->handler->handle($command);

            return $this->responder->render();
        } catch (Throwable $e) {
            throw new BadRequestHttpException(null, $e);
        }
    }
}
