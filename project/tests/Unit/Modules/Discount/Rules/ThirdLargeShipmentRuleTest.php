<?php

namespace Tests\Unit\Modules\Discount\Rules;

use App\Models\Transaction;
use App\Modules\Discount\Interfaces\Providers\TransactionProviderInterface;
use App\Modules\Discount\Rules\MonthlyLimitRule;
use App\Modules\Discount\Rules\ThirdLargeShipmentRule;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class ThirdLargeShipmentRuleTest extends TestCase
{
    private MockObject|TransactionProviderInterface $transactionProvider;

    private ThirdLargeShipmentRule $rule;

    public function setUp(): void
    {
        $this->transactionProvider = $this->createMock(TransactionProviderInterface::class);

        $this->rule = new ThirdLargeShipmentRule($this->transactionProvider);
    }

    public function testApplyRule(): void
    {
        $transaction = $this->mockTransaction('2023-03-05', 'L', 'LP', 2.1, 0);

        $transactions = [
            $this->mockTransaction('2023-03-03', 'L', 'LP', 2.5, 0.2),
            $this->mockTransaction('2023-03-04', 'L', 'LP', 2.1, 0.4),
            $this->mockTransaction('2023-03-05', 'L', 'LP', 2.1, 0),
        ];

        $this
            ->transactionProvider
            ->method('getCurrentMonthCarrierAndSizeTransactions')
            ->willReturn($transactions)
        ;

        $result = $this->rule->applyRule($transaction, $transactions);

        $this->assertEquals(
            $this->mockTransaction('2023-03-05', 'L', 'LP', 0, 2.1),
            $result,
        );
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
