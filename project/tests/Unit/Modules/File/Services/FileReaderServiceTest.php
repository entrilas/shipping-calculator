<?php declare(strict_types=1);

namespace Tests\Modules\File\Services;

use App\Modules\File\Interfaces\FileReaderInterface;
use App\Modules\File\Interfaces\Services\FileReaderServiceInterface;
use App\Modules\File\Services\FileReaderService;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class FileReaderServiceTest extends TestCase
{
    private MockObject|FileReaderInterface $transactionFileReader;
    private MockObject|FileReaderInterface $providerFileReader;

    private FileReaderServiceInterface $fileReaderService;

    public function setUp(): void
    {
        $this->transactionFileReader = $this->createMock(FileReaderInterface::class);
        $this->providerFileReader = $this->createMock(FileReaderInterface::class);

        $this->fileReaderService = new FileReaderService($this->transactionFileReader, $this->providerFileReader);
    }

    public function testReadTransactionsFile(): void
    {
        $filePath = 'path/to/transactions/file';
        $data = ['transaction 1', 'transaction 2'];

        $this
            ->transactionFileReader
            ->method('readFile')
            ->with($filePath)
            ->willReturn($data)
        ;

        $this->assertSame($data, $this->fileReaderService->readTransactionsFile($filePath));
    }

    public function testReadProvidersFile(): void
    {
        $filePath = 'path/to/providers/file';
        $data = ['provider 1', 'provider 2'];

        $this
            ->providerFileReader
            ->method('readFile')
            ->with($filePath)
            ->willReturn($data)
        ;

        $this->assertSame($data, $this->fileReaderService->readCarriersFile($filePath));
    }
}
