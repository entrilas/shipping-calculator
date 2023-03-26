<?php declare(strict_types=1);

namespace Tests\Unit\Modules\Discount\Providers;

use App\Models\Transaction;
use App\Modules\Discount\Interfaces\Providers\TransactionProviderInterface;
use App\Modules\Discount\Providers\TransactionProvider;
use App\Modules\File\Interfaces\Services\FileReaderServiceInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class TransactionProviderTest extends TestCase
{
    private MockObject|FileReaderServiceInterface $fileReaderService;

    private TransactionProviderInterface $transactionProvider;

    protected function setUp(): void
    {
        $this->fileReaderService = $this->createMock(FileReaderServiceInterface::class);
    }

    public function testGetAllTransactions(): void
    {
        $transactions = [
            $this->mockTransaction('2022-01-01', 'Carrier A', 'M', 10.0, 2),
            $this->mockTransaction('2022-01-02', 'Carrier B', 'S', 5.0, 2),
            $this->mockTransaction('2022-01-03', 'Carrier C', 'L', 20.0, 2),
        ];

        $this
            ->fileReaderService
            ->method('readTransactionsFile')
            ->willReturn($transactions)
        ;

        $this->transactionProvider = new TransactionProvider($this->fileReaderService);

        $result = $this->transactionProvider->getAllTransactions();

        $this->assertEquals($transactions, $result);
    }

    public function testGetCurrentMonthTransactions(): void
    {
        $currentTransaction = $this->mockTransaction('2022-01-02', 'Carrier A', 'M', 10.0, 2);

        $transactions = [
            $currentTransaction,
            $this->mockTransaction('2022-03-02', 'Carrier B', 'S', 5.0, 2.1),
            $this->mockTransaction('2022-03-02', 'Carrier C', 'L', 20.0, 2.2),
        ];

        $this
            ->fileReaderService
            ->method('readTransactionsFile')
            ->willReturn($transactions)
        ;

        $this->transactionProvider = new TransactionProvider($this->fileReaderService);

        $result = $this->transactionProvider->getCurrentMonthTransactions($currentTransaction);

        $this->assertEquals([$currentTransaction], $result);
    }

    public function testGetCurrentMonthCarrierAndSizeTransactions(): void
    {
        $currentTransaction = $this->mockTransaction('2022-01-02', 'M', 'Carrier A', 10.0, 0.1);

        $transactions = [
            $currentTransaction,
            $this->mockTransaction('2022-01-02', 'M', 'Carrier A', 15.0, 0.1),
            $this->mockTransaction('2022-03-02', 'M', 'Carrier A', 5.0, 0.1),
        ];

        $this
            ->fileReaderService
            ->method('readTransactionsFile')
            ->willReturn($transactions)
        ;

        $this->transactionProvider = new TransactionProvider($this->fileReaderService);

        $result = $this->transactionProvider->getCurrentMonthCarrierAndSizeTransactions(
            $currentTransaction,
            'Carrier A',
            'M'
        );

        $this->assertEquals([$transactions[0], $transactions[1]], $result);
    }

    private function mockTransaction(
        ?string $date = null,
        ?string $packageSize = null,
        ?string $carrierCode = null,
        ?float $shippingPrice = null,
        ?float $discount = null,
    ): Transaction {
        $transaction = new Transaction();

        $transaction->setTransactionDate($date);
        $transaction->setPackageSize($packageSize);
        $transaction->setCarrierCode($carrierCode);
        $transaction->setShippingPrice($shippingPrice);
        $transaction->setDiscount($discount);

        return $transaction;
    }
}
