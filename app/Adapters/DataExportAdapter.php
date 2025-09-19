<?php

namespace App\Adapters;

interface DataExportAdapter
{
    public function export(array $data): string;
}
