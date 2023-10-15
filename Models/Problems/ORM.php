<?php

namespace Models\Problems;

use Models\TableRow;

class ORM
{
    static string $problemsResourcesPath = __DIR__ . '/../../' .'resources/problems/';

    static function getProblem(int $id): Problem
    {
        $path = self::$problemsResourcesPath . $id . '.json';
        if (!file_exists($path)) {
            throw new \Exception("File for Problem ID: {$id} does not exist.");
        }

        $jsonData = file_get_contents($path);
        $data = json_decode($jsonData, true);

        return new Problem(
            $data['id'],
            $data['title'],
            $data['theme'],
            $data['uml']
        );
    }

    static function getTableRows(): array
    {
        $tableRows = [];
        $files = glob(self::$problemsResourcesPath . '*.json');
        foreach ($files as $file) {
            $jsonData = file_get_contents($file);
            $data = json_decode($jsonData, true);

            $tableRows[] = new TableRow(
                $data['id'],
                $data['title'],
                $data['theme']
            );
        }

        usort($tableRows, function($a, $b) {
            return $a->getId() - $b->getId();
        });

        return $tableRows;
    }
}