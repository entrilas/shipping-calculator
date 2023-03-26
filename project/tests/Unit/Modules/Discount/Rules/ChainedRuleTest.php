<?php declare(strict_types=1);

namespace Tests\Unit\Modules\Discount\Rules;

use App\Models\Transaction;
use App\Modules\Discount\Rules\ChainedRule;
use App\Modules\Discount\Rules\MonthlyLimitRule;
use App\Modules\Discount\Rules\SmallPackageRule;
use App\Modules\Discount\Rules\ThirdLargeShipmentRule;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class ChainedRuleTest extends TestCase
{
    private MockObject|MonthlyLimitRule $monthlyLimitRule;
    private MockObject|SmallPackageRule $smallPackageRule;
    private MockObject|ThirdLargeShipmentRule $thirdLargeShipmentRule;

    private ChainedRule $chainedRule;

    public function setUp(): void
    {
        $this->monthlyLimitRule = $this->createMock(MonthlyLimitRule::class);
        $this->smallPackageRule = $this->createMock(SmallPackageRule::class);
        $this->thirdLargeShipmentRule = $this->createMock(ThirdLargeShipmentRule::class);

        $this->chainedRule = new ChainedRule(
            $this->monthlyLimitRule,
            $this->smallPackageRule,
            $this->thirdLargeShipmentRule,
        );
    }

    public function testApplyRule(): void
    {
        $transactions = [
            new Transaction('2012-03-03', 'M', 'LP'),
            new Transaction('2012-04-03', 'S', 'MR'),
            new Transaction('2012-05-03', 'L', 'MR'),
        ];

        $this
            ->monthlyLimitRule
            ->method('applyRule')
            ->with($transactions[0], $transactions)
            ->willReturn($monthlyLimitRuleTransaction = new Transaction('2012-03-03', 'M', 'LP'))
        ;

        $this
            ->smallPackageRule
            ->method('applyRule')
            ->with($monthlyLimitRuleTransaction, $transactions)
            ->willReturn($smallPackageRuleTransaction = new Transaction('2012-04-03', 'M', 'LP'))
        ;

        $this
            ->thirdLargeShipmentRule
            ->method('applyRule')
            ->with($smallPackageRuleTransaction, $transactions)
            ->willReturn($thirdLargeShipmentRuleTransaction = new Transaction('2012-05-03', 'M', 'LP'))
        ;

        $this->assertSame(
            $thirdLargeShipmentRuleTransaction,
            $this->chainedRule->applyRule($transactions[0], $transactions),
        );
    }
}
