<?php

namespace App\Adapters;

class CsvExportAdapter implements DataExportAdapter
{
    public function export(array $data): string
    {
        if (empty($data)) {
            return '';
        }
        $output = '';
        $header = array_keys((array) $data[0]);
        $output .= implode(',', $header) . "\n";
        foreach ($data as $row) {
            $row = (array) $row;
            $output .= implode(',', array_map(function($v) {
                return '"' . str_replace('"', '""', $v) . '"';
            }, $row)) . "\n";
        }
        return $output;
    }
}
