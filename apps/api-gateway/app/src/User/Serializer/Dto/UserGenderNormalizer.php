<?php

declare(strict_types=1);

namespace App\User\Serializer\Dto;

use App\Serializer\AbstractModelNormalizer;
use App\User\Model\Dto\UserMedium;
use App\User\Model\UserGender;
use Symfony\Contracts\Translation\TranslatorInterface;

final class UserGenderNormalizer extends AbstractModelNormalizer
{
    private TranslatorInterface $translator;

    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    public function normalizeModel($user, string $format = null, array $context = []): void
    {
        if (!$user instanceof UserMedium) {
            return;
        }

        $user->setGenderReading(UserGender::getReadingChoice($user->getGender(), $this->translator));
    }

    public function supportsNormalizationModel($user, string $format = null, array $context = []): bool
    {
        return $user instanceof UserMedium;
    }
}
