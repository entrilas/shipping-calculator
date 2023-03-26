<?php declare(strict_types=1);

namespace Tests\Unit\Modules\Discount\Rules;

use App\Models\Transaction;
use App\Modules\Discount\Interfaces\Providers\TransactionProviderInterface;
use App\Modules\Discount\Rules\MonthlyLimitRule;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class MonthlyLimitRuleTest extends TestCase
{
    private MockObject|TransactionProviderInterface $transactionProvider;

    private MonthlyLimitRule $rule;

    public function setUp(): void
    {
        $this->transactionProvider = $this->createMock(TransactionProviderInterface::class);

        $this->rule = new MonthlyLimitRule($this->transactionProvider);
    }

    public function testApplyRule(): void
    {
        $transaction = $this->mockTransaction('2023-03-03', 'S', 'LP', 2.1, 0.4);
        $transactions = [
            $this->mockTransaction('2023-03-03', 'S', 'MP', 2.5, 9.8),
            $this->mockTransaction('2023-03-03', 'S', 'LP', 2.1, 0.4),
        ];

        $this
            ->transactionProvider
            ->method('getCurrentMonthTransactions')
            ->willReturn($transactions)
        ;

        $result = $this->rule->applyRule($transaction, $transactions);

        $this->assertEquals(
            $this->mockTransaction('2023-03-03', 'S', 'LP', 2.3, 0.2),
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
