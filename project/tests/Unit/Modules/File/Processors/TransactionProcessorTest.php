<?php declare(strict_types=1);

use App\Models\Transaction;
use App\Modules\File\Processors\TransactionProcessor;
use PHPUnit\Framework\TestCase;

class TransactionProcessorTest extends TestCase
{
    private TransactionProcessor $processor;

    public function setUp(): void
    {
        $this->processor = new TransactionProcessor();
    }

    /** @dataProvider provideTestProcessData */
    public function testProcessData(?string $date, ?string $packageSize, ?string $carrierCode): void
    {
        $input = [$date, $packageSize, $carrierCode];

        $transaction = new Transaction();

        $transaction->setTransactionDate($date);
        $transaction->setPackageSize($packageSize);
        $transaction->setCarrierCode($carrierCode);

        $this->assertEquals($transaction, $this->processor->processData($input));
    }

    private function provideTestProcessData(): array
    {
        return [
            'all values assigned' => ['2023-03-03', 'L', 'DP'],
            'some values nullable' => ['2023-03-04', null, null],
        ];
    }
}
