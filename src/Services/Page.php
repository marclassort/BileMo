<?php

namespace App\Services;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

class Page
{
    private Collection $content;
    private int $totalElements;
    private int $offset;
    private int $limit;

    public function __construct()
    {
        $this->content = new ArrayCollection();
    }

    public static function of(Collection $content, int $totalElements, int $offset = 0, int $limit = 20): Page
    {
        $page = new Page();
        $page->setContent($content)
            ->setTotalElements($totalElements)
            ->setOffset($offset)
            ->setLimit($limit);

        return $page;
    }

    public function getContent(): Collection
    {
        return $this->content;
    }

    public function setContent(Collection $content)
    {
        $this->content = $content;

        return $this;
    }

    public function setTotalElements(int $totalElements)
    {
        $this->totalElements = $totalElements;

        return $this;
    }

    public function setOffset(int $offset)
    {
        $this->offset = $offset;

        return $this;
    }

    public function setLimit(int $limit)
    {
        $this->limit = $limit;

        return $this;
    }
}
