<?php declare(strict_types=1);

namespace App\Modules\File\Services;

use App\Modules\File\Interfaces\FileReaderInterface;
use App\Modules\File\Interfaces\Services\FileReaderServiceInterface;

class FileReaderService implements FileReaderServiceInterface
{
    public function __construct(
        private readonly FileReaderInterface $transactionFileReader,
        private readonly FileReaderInterface $providerFileReader,
    ) {
    }

    public function readTransactionsFile(string $filePath): array
    {
        return $this->transactionFileReader->readFile($filePath);
    }

    public function readProvidersFile(string $filePath): array
    {
        return $this->providerFileReader->readFile($filePath);
    }
}
