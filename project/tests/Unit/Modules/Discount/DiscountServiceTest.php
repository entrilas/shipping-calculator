<?php declare(strict_types=1);

namespace Tests\Unit\Modules\Discount;

use App\Models\Carrier;
use App\Models\Transaction;
use App\Modules\Discount\Interfaces\Providers\CarrierProviderInterface;
use App\Modules\Discount\Interfaces\Providers\TransactionProviderInterface;
use App\Modules\Discount\Interfaces\Rules\RuleInterface;
use App\Modules\Discount\Interfaces\Transformer\TransactionTransformerInterface;
use App\Modules\Discount\Services\DiscountService;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class DiscountServiceTest extends TestCase
{
    private MockObject|TransactionProviderInterface $chainedRules;
    private MockObject|TransactionTransformerInterface $transactionTransformer;
    private MockObject|CarrierProviderInterface $carrierProvider;
    private MockObject|TransactionProviderInterface $transactionProvider;

    private DiscountService $discountService;

    protected function setUp(): void
    {
        $this->chainedRules = $this->createMock(RuleInterface::class);
        $this->transactionTransformer = $this->createMock(TransactionTransformerInterface::class);
        $this->carrierProvider = $this->createMock(CarrierProviderInterface::class);
        $this->transactionProvider = $this->createMock(TransactionProviderInterface::class);

        $this->discountService = new DiscountService(
            $this->chainedRules,
            $this->transactionTransformer,
            $this->carrierProvider,
            $this->transactionProvider,
        );
    }

    public function testCalculateDiscounts(): void
    {
        $this
            ->transactionProvider
            ->method('getAllTransactions')
            ->willReturn([
                $this->mockTransaction('2022-01-01', 'Carrier A', 'M', 10.0, 2),
                $this->mockTransaction('2022-01-02', 'Carrier B', 'S', 5.0, 2),
                $this->mockTransaction('2022-01-03', 'Carrier C', 'L', 20.0, 2),
            ])
        ;

        $this
            ->transactionTransformer
            ->method('transformShipmentPrice')
            ->willReturn($transaction = $this->mockTransaction('2022-01-01', 'Carrier A', 'M', 10.0, 2))
        ;

        $this
            ->carrierProvider
            ->method('getAllCarriers')
            ->willReturn([
                $this->mockCarrier('Carrier A', 'M', 10.0),
                $this->mockCarrier('Carrier B', 'S', 5.0),
                $this->mockCarrier('Carrier C', 'L', 20.0),
            ])
        ;

        $this
            ->chainedRules
            ->method('applyRule')
            ->willReturn($transaction)
        ;

        $this->assertEquals([$transaction, $transaction, $transaction], $this->discountService->calculateDiscounts());
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

    private function mockCarrier(?string $carrierCode, ?string $packageSize, ?float $price): Carrier
    {
        $carrier = new Carrier();

        $carrier->setCarrierCode($carrierCode);
        $carrier->setPackageSize($packageSize);
        $carrier->setPrice($price);

        return $carrier;
    }
}
