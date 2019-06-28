<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;

class FeedCategory
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $name;

    /**
     * @var Feed[]|ArrayCollection
     */
    private $feeds;

    public function __toString()
    {
        return $this->name;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return Feed[]|ArrayCollection
     */
    public function getFeeds()
    {
        return $this->feeds;
    }

    /**
     * @param Feed[]|ArrayCollection $feeds
     */
    public function setFeeds($feeds): void
    {
        $this->feeds = $feeds;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }
}
