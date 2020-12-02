<?php

declare(strict_types=1);

namespace App\Story\Entity;

use App\Entity\AbstractEntity;
use App\User\Entity\User;
use App\User\Entity\UserableInterface;
use App\User\Entity\UserableTrait;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(name="sty_story_rating", uniqueConstraints={@ORM\UniqueConstraint(name="UNIQ_STORY_RATING", columns={"story_id", "user_id"})})
 * @ORM\Entity(repositoryClass="App\Story\Repository\StoryRatingRepository")
 * @UniqueEntity(
 *      fields = {"story", "user"}
 * )
 */
class StoryRating extends AbstractEntity implements UserableInterface
{
    use UserableTrait;

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
     * @ORM\ManyToOne(targetEntity="App\Story\Entity\Story", inversedBy="storyRatings")
     * @ORM\JoinColumn(name="story_id", referencedColumnName="id", nullable=false)
     */
    private Story $story;

    /**
     * @ORM\ManyToOne(targetEntity="App\User\Entity\User", inversedBy="storyRatings")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", nullable=false)
     */
    private User $user;

    public function __construct(int $rate, Story $story, User $user)
    {
        parent::__construct();

        // init zero values
        $this->rate = 5;

        // init values
        $this->setRate($rate)
            ->setStory($story)
            ->setUser($user)
        ;
    }

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

    public function setUser(User $user): self
    {
        $this->user = $user;
        $user->addStoryRating($this);

        return $this;
    }
}
