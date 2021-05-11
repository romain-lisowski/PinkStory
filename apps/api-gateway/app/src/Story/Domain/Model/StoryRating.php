<?php

declare(strict_types=1);

namespace App\Story\Domain\Model;

use App\Common\Domain\Model\AbstractEntity;
use App\User\Domain\Model\User;
use App\User\Domain\Model\UserableInterface;
use App\User\Domain\Model\UserInterface;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Story\Infrastructure\Doctrine\Repository\StoryRatingDoctrineORMRepository")
 * @ORM\Table(name="sty_story_rating", uniqueConstraints={@ORM\UniqueConstraint(name="UNIQ_STORY_RATING", columns={"story_id", "user_id"})})
 * @UniqueEntity(
 *      fields = {"story", "user"}
 * )
 */
class StoryRating extends AbstractEntity implements UserableInterface
{
    /**
     * @Assert\NotBlank
     * @Assert\Range(
     *      min = 0,
     *      max = 5,
     * )
     * @ORM\Column(name="rate", type="integer")
     */
    private int $rate;

    /**
     * @ORM\ManyToOne(targetEntity="App\Story\Domain\Model\Story", inversedBy="storyRatings")
     * @ORM\JoinColumn(name="story_id", referencedColumnName="id", nullable=false)
     */
    private Story $story;

    /**
     * @ORM\ManyToOne(targetEntity="App\User\Domain\Model\User", inversedBy="storyRatings")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", nullable=false)
     */
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

    public function updateRate(int $rate): self
    {
        $this->setRate($rate);
        $this->updateLastUpdatedAt();

        return $this;
    }

    public function getStory(): Story
    {
        return $this->story;
    }

    public function setStory(Story $story): self
    {
        $this->story = $story;
        $story->addStoryRating($this);

        return $this;
    }

    public function getUser(): UserInterface
    {
        return $this->user;
    }

    public function setUser(User $user): self
    {
        $this->user = $user;
        $user->addStoryRating($this);

        return $this;
    }
}
