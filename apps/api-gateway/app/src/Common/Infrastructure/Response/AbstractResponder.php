<?php

declare(strict_types=1);

namespace App\Common\Infrastructure\Response;

use App\Common\Presentation\Response\ResponderInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\OptionsResolver\OptionsResolver;

abstract class AbstractResponder implements ResponderInterface
{
    public function render(array $data = [], array $options = []): Response
    {
        $resolver = new OptionsResolver();
        $this->configureOptions($resolver);
        $options = $resolver->resolve($options);

        return $this->renderResponse($data, $options);
    }

    abstract protected function renderResponse(array $data = [], array $options = []): Response;

    abstract protected function configureOptions(OptionsResolver $resolver): void;
}
