<?php

declare(strict_types=1);

namespace App\File\Serializer;

use App\File\Model\ImageableInterface;
use App\Serializer\AbstractModelNormalizer;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class ImageableNormalizer extends AbstractModelNormalizer
{
    private ParameterBagInterface $params;

    public function __construct(ParameterBagInterface $params)
    {
        $this->params = $params;
    }

    public function normalizeModel($imageable, string $format = null, array $context = []): void
    {
        if (!$imageable instanceof ImageableInterface) {
            return;
        }

        $imageable->setImageUrl($this->params->get('project_file_manager_dsn'));
    }

    public function supportsNormalizationModel($imageable, string $format = null, array $context = []): bool
    {
        return $imageable instanceof ImageableInterface;
    }
}
