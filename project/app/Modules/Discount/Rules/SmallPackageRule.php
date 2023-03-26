<?php declare(strict_types=1);

/**
 * The SmallPackageRule class implements the RuleInterface and is responsible for applying the discount for small packages.
 * It checks if the transaction package size is "S" and if the shipping price can be reduced based on the lowest price carrier available.
 * If so, the discount is calculated and applied to the transaction shipping price.
 */

namespace App\Modules\Discount\Rules;

use App\Models\Transaction;
use App\Modules\Discount\Interfaces\Providers\CarrierProviderInterface;
use App\Modules\Discount\Interfaces\Rules\RuleInterface;

class SmallPackageRule implements RuleInterface
{
    private const SMALL_PACKAGE = 'S';

    public function __construct(
        private CarrierProviderInterface $carrierProvider,
    ) {
    }

    /**
     * @param Transaction $transaction
     * @param Transaction[] $transactions
     * @return array
     */
    public function applyRule(Transaction $transaction, array $transactions): Transaction
    {
        $lowestPrice = $this->carrierProvider->getLowestPriceCarrier()->getPrice();
        $currentPrice = $transaction->getShippingPrice();

        if (($discount = $currentPrice - $lowestPrice) > 0 && $transaction->getPackageSize() === self::SMALL_PACKAGE) {
           $updatedPrice = $currentPrice - $discount;
           $transaction->setShippingPrice($updatedPrice);
           $transaction->setDiscount($discount);
        }

        return $transaction;
    }
}
