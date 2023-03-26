<?php declare(strict_types=1);

/**
 * The FileReaderService class is responsible for reading files and returning their contents in the form of an array.
 * This class implements FileReaderServiceInterface and depends on FileReaderInterface for file reading. In this scenario
 * two FileReaders will get built with the Processors accordingly (CarrierProcessor, TransactionProcessor)
 */

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

    public function readCarriersFile(string $filePath): array
    {
        return $this->providerFileReader->readFile($filePath);
    }
}
