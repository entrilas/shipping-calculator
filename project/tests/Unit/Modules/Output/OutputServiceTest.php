<?php declare(strict_types=1);

namespace Unit\Modules\Output;

use App\Models\Transaction;
use App\Modules\Output\OutputService;
use PHPUnit\Framework\TestCase;

class OutputServiceTest extends TestCase
{
    private OutputService $outputService;

    protected function setUp(): void
    {
        $this->outputService = new OutputService();
    }

    public function testPrintConsole(): void
    {
        $transactions = [
            $this->mockTransaction('2022-01-01', 'Carrier A', 'M', 10.0, 2),
            $this->mockTransaction('2022-01-01', 'Carrier A', 'M', 10.0, 2),
        ];

        $expectedOutput = <<<TEXT
        2022-01-01 Carrier A M 10 2
        2022-01-01 Carrier A M 10 2

        TEXT;

        $this->expectOutputString($expectedOutput);

        $this->outputService->printConsole($transactions);
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
