<?php

declare(strict_types=1);

namespace App\File;

use App\Serializer\AbstractEntityNormalizer;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class ImageableNormalizer extends AbstractEntityNormalizer
{
    private ParameterBagInterface $params;

    public function __construct(ParameterBagInterface $params)
    {
        $this->params = $params;
    }

    public function normalizeEntity($imageable, string $format = null, array $context = []): void
    {
        if (!$imageable instanceof ImageableInterface) {
            return;
        }

        $imageable->setImageUrl($this->params->get('project_file_manager_dsn'));
    }

    public function supportsNormalizationEntity($imageable, string $format = null, array $context = []): bool
    {
        return $imageable instanceof ImageableInterface;
    }
}
