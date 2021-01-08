<?php

declare(strict_types=1);

namespace App\Story\Domain\Model;

use DateTime;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Uid\Uuid;

/**
 * @ORM\Entity
 * @ORM\Table(name="sty_story")
 */
class Story
{
    /**
     * @Assert\NotBlank
     * @ORM\Id()
     * @ORM\Column(name="id", type="uuid", unique=true)
     */
    private Uuid $id;

    /**
     * @Assert\NotBlank
     * @ORM\Column(name="title", type="string", length=255)
     */
    private string $title;

    /**
     * @Assert\NotBlank
     * @ORM\Column(name="title_slug", type="string", length=255)
     */
    private string $titleSlug;

    /**
     * @Assert\NotBlank
     * @ORM\Column(name="content", type="text")
     */
    private string $content;

    /**
     * @Assert\NotBlank
     * @Assert\Length(
     *      max = 140
     * )
     * @ORM\Column(name="extract", type="text")
     */
    private string $extract;

    /**
     * @ORM\ManyToOne(targetEntity="App\Story\Domain\Model\Story", inversedBy="children")
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="id")
     */
    private ?Story $parent;

    /**
     * @ORM\OneToMany(targetEntity="App\Story\Domain\Model\Story", mappedBy="parent", cascade={"remove"})
     * @ORM\OrderBy({"position" = "ASC"})
     *
     * @var Story[]
     */
    private Collection $children;

    /**
     * @ORM\Column(name="position", type="integer", nullable=true)
     */
    private ?int $position;

    /**
     * @Assert\NotBlank
     * @ORM\Column(name="created_at", type="datetime")
     */
    private DateTime $createdAt;

    /**
     * @Assert\NotBlank
     * @ORM\Column(name="last_updated_at", type="datetime")
     */
    private DateTime $lastUpdatedAt;
}
