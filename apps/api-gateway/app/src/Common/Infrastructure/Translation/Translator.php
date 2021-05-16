<?php

declare(strict_types=1);

namespace App\Common\Infrastructure\Translation;

use App\Common\Domain\Translation\TranslatorInterface as DomainTranslatorInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

final class Translator implements DomainTranslatorInterface
{
    private TranslatorInterface $translator;

    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    public function trans(string $id, array $parameters = [], ?string $domain = null, ?string $locale = null): string
    {
        return $this->translator->trans($id, $parameters, $domain, $locale);
    }
}
