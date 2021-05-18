<?php

declare(strict_types=1);

namespace App\Story\Query\Model;

use App\Common\Domain\Model\EditableInterface;
use App\Common\Domain\Model\EditableTrait;
use App\User\Domain\Model\UserableInterface;
use App\User\Domain\Model\UserInterface;
use App\User\Query\Model\User;

class StoryRatingUpdate implements UserableInterface, EditableInterface
{
    use EditableTrait;

    private int $rate;
    private Story $story;
    private User $user;

    public function getRate(): int
    {
        return $this->rate;
    }

    public function setRate(int $rate): self
    {
        $this->rate = $rate;

        return $this;
    }

    public function getStory(): Story
    {
        return $this->story;
    }

    public function setStory(Story $story): self
    {
        $this->story = $story;

        return $this;
    }

    public function getUser(): UserInterface
    {
        return $this->user;
    }

    public function setUser(User $user): self
    {
        $this->user = $user;

        return $this;
    }
}
