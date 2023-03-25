<?php declare(strict_types=1);

namespace App\Modules\File\Interfaces\Services;

interface FileReaderServiceInterface
{
    public function readTransactionsFile(string $filePath): array;
    public function readProvidersFile(string $filePath): array;
}
