<?php declare(strict_types=1);

namespace App\Modules\File\Interfaces\Processors;

interface ProcessorInterface
{
    public function processData(array $values): object;
}
