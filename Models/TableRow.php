<?php

namespace Models;

class TableRow
{
    protected int $id;
    protected string $title;
    protected string $theme;

    public function __construct(
        int $id, string $title, string $theme
    )
    {
        $this->id = $id;
        $this->title = $title;
        $this->theme = $theme;
    }

    public function getID(): int
    {
        return $this->id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getTheme(): string
    {
        return $this->theme;
    }
}