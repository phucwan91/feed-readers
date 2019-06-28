<?php

namespace App\Entity;

class Feed
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var FeedCategory
     */
    private $category;

    /**
     * @var string
     */
    private $data;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return FeedCategory
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * @param FeedCategory $category
     */
    public function setCategory(FeedCategory $category): void
    {
        $this->category = $category;
    }

    /**
     * @return string
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @param string $data
     */
    public function setData(string $data): void
    {
        $this->data = $data;
    }
}
