<?php

namespace App\Helper;

class DataTablesJsonFormatter
{
    public static function format(array $items, int $draw): array
    {
        $json = [
            'draw' => $draw,
            'recordsTotal' => count($items),
            'recordsFiltered' => count($items),
            'data' => json_encode($items)
        ];

        return $json;
    }
}