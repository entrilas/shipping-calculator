<?php

namespace Tests\Modules\Discount\Rules;

use App\Models\Carrier;
use App\Models\Transaction;
use App\Modules\Discount\Interfaces\Providers\CarrierProviderInterface;
use App\Modules\Discount\Rules\SmallPackageRule;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class SmallPackageRuleTest extends TestCase
{
    private MockObject|CarrierProviderInterface $carrierProvider;

    private SmallPackageRule $rule;

    public function setUp(): void
    {
        $this->carrierProvider = $this->createMock(CarrierProviderInterface::class);

        $this->rule = new SmallPackageRule($this->carrierProvider);
    }

    public function testApplyRuleWithDiscount(): void
    {
        $transaction = $this->mockTransaction('2023-03-03', 'S', 'LP', 2.5, 0);
        $transactions = [
            $this->mockTransaction('2023-03-03', 'S', 'LP', 2.5, 0),
            $this->mockTransaction('2023-03-03', 'S', 'LP', 2.5, 0),
        ];

        $this
            ->carrierProvider
            ->method('getLowestPriceCarrier')
            ->willReturn($this->mockCarrier('MP', 'S', 2))
        ;

        $result = $this->rule->applyRule($transaction, $transactions);

        $this->assertSame(2.0, $result->getShippingPrice());
    }

    private function mockCarrier(?string $carrierCode, ?string $packageSize, ?float $price): Carrier
    {
        $carrier = new Carrier();

        $carrier->setCarrierCode($carrierCode);
        $carrier->setPackageSize($packageSize);
        $carrier->setPrice($price);

        return $carrier;
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
