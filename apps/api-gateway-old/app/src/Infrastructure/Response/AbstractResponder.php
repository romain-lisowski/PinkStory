<?php

declare(strict_types=1);

namespace App\Infrastructure\Response;

use App\Presentation\Response\ResponderException;
use App\Presentation\Response\ResponderInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Throwable;

abstract class AbstractResponder implements ResponderInterface
{
    public function render(array $data = [], array $options = []): Response
    {
        try {
            $resolver = new OptionsResolver();
            $this->configureOptions($resolver);
            $options = $resolver->resolve($options);

            return $this->renderResponse($data, $options);
        } catch (Throwable $e) {
            throw new ResponderException('', 0, $e);
        }
    }

    abstract protected function renderResponse(array $data = [], array $options = []): Response;

    abstract protected function configureOptions(OptionsResolver $resolver): void;
}
