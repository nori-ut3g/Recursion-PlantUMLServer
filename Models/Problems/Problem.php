<?php

namespace Models\Problems;

use Models\TableRow;

class Problem extends TableRow
{

    private string $uml;

    public function __construct(
        int $id, string $title, string $theme,
        string $uml
    )
    {
        parent::__construct($id, $title, $theme);
        $this->uml = $uml;
    }

    public function getUML(): string
    {
        return $this->uml;
    }
}