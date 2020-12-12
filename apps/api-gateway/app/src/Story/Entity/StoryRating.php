<?php

declare(strict_types=1);

namespace App\Story\Entity;

use App\Model\Entity\AbstractEntity;
use App\User\Model\Entity\User;
use App\User\Model\UserEditableInterface;
use App\User\Model\UserEditableTrait;
use App\User\Model\UserInterface;
use Doctrine\ORM\Mapping as ORM;
use InvalidArgumentException;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(name="sty_story_rating", uniqueConstraints={@ORM\UniqueConstraint(name="UNIQ_STORY_RATING", columns={"story_id", "user_id"})})
 * @ORM\Entity(repositoryClass="App\Story\Repository\StoryRatingRepository")
 * @UniqueEntity(
 *      fields = {"story", "user"}
 * )
 */
class StoryRating extends AbstractEntity implements UserEditableInterface
{
    use UserEditableTrait;

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
     * @ORM\ManyToOne(targetEntity="App\User\Model\Entity\User", inversedBy="storyRatings")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", nullable=false)
     */
    private UserInterface $user;

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

    public function setUser(UserInterface $user): self
    {
        if (!$user instanceof User) {
            throw new InvalidArgumentException();
        }

        $this->user = $user;
        $user->addStoryRating($this);

        return $this;
    }
}
