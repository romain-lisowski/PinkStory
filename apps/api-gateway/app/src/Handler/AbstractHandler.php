<?php

declare(strict_types=1);

namespace App\Handler;

use InvalidArgumentException;
use ReflectionClass;
use Symfony\Component\Security\Core\User\UserInterface;

abstract class AbstractHandler implements HandlerInterface
{
    protected HandlerableInterface $handlerable;
    protected ?UserInterface $currentUser;

    public function setHandlerable(HandlerableInterface $handlerable): self
    {
        if ($handlerable->getHandler() !== (new ReflectionClass($this))->getName()) {
            throw new InvalidArgumentException();
        }

        $this->handlerable = $handlerable;

        return $this;
    }

    public function setCurrentUser(?UserInterface $currentUser): self
    {
        $this->currentUser = $currentUser;

        return $this;
    }

    abstract public function handle();
}
