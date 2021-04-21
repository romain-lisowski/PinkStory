<?php

declare(strict_types=1);

namespace App\User\Presentation\Action;

use App\Common\Domain\Translation\TranslatorInterface;
use App\Common\Presentation\Response\ResponderInterface;
use App\User\Domain\Model\UserGender;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/user-gender/search", name="user_gender_search", methods={"GET"})
 */
final class UserGenderSearchAction
{
    private ResponderInterface $responder;
    private TranslatorInterface $translator;

    public function __construct(ResponderInterface $responder, TranslatorInterface $translator)
    {
        $this->responder = $responder;
        $this->translator = $translator;
    }

    public function __invoke(): Response
    {
        return $this->responder->render([
            'user-genders' => UserGender::getReadingChoices($this->translator),
        ]);
    }
}
