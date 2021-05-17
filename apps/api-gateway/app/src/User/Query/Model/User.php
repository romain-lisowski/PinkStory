<?php

declare(strict_types=1);

namespace App\User\Query\Model;

use App\Common\Domain\Model\EditableInterface;
use App\Common\Domain\Model\EditableTrait;
use App\Common\Domain\Model\IdentifiableInterface;
use App\Common\Domain\Model\IdentifiableTrait;
use App\Common\Query\Model\AbstractModel;
use App\User\Domain\Model\UserableInterface;
use App\User\Domain\Model\UserInterface;
use Symfony\Component\Serializer\Annotation as Serializer;

class User extends AbstractModel implements IdentifiableInterface, UserInterface, UserableInterface, EditableInterface
{
    use IdentifiableTrait;
    use EditableTrait;

    private string $id;

    public function getId(): string
    {
        return $this->id;
    }

    public function setId(string $id): self
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @Serializer\Ignore()
     */
    public function getUser(): UserInterface
    {
        return $this;
    }
}
