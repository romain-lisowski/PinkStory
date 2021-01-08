<?php

declare(strict_types=1);

namespace App\User\Action;

use App\Action\AbstractAction;
use App\Responder\ResponderInterface;
use App\User\Model\UserGender;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * @Route("/user-gender/search", name="user_gender_search", methods={"GET"})
 */
final class UserGenderSearchAction extends AbstractAction
{
    private TranslatorInterface $translator;
    private ResponderInterface $responder;

    public function __construct(TranslatorInterface $translator, ResponderInterface $responder)
    {
        $this->translator = $translator;
        $this->responder = $responder;
    }

    public function run(Request $request): Response
    {
        return $this->responder->render([
            'user-genders' => UserGender::getReadingChoices($this->translator),
        ]);
    }
}
