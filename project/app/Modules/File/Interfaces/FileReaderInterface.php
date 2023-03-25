<?php declare(strict_types=1);

namespace App\Modules\File\Interfaces;

interface FileReaderInterface
{
    public function readFile(string $filePath): array;
}
