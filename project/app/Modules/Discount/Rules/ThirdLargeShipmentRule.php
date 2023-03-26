<?php declare(strict_types=1);

/**
 * Implements a discount rule that provides free shipping for the third large shipment made through LP carrier and L size packages
 * in a given month. The rule checks the transaction history to determine if the current transaction is eligible for
 * the discount. If the transaction is eligible, the shipping price is set to zero and the discount amount is set to the
 * original shipping price. Otherwise, the transaction is returned unchanged.
 */

namespace App\Modules\Discount\Rules;

use App\Models\Transaction;
use App\Modules\Discount\Interfaces\Providers\TransactionProviderInterface;
use App\Modules\Discount\Interfaces\Rules\RuleInterface;

class ThirdLargeShipmentRule implements RuleInterface
{
    private const LP_CARRIER_CODE = 'LP';
    private const L_PACKAGE_SIZE = 'L';
    private const THIRD_MONTH = 3;
    private const FREE_SHIPPING_PRICE = 0;

    public function __construct(
        private TransactionProviderInterface $transactionProvider,
    ) {
    }

    /**
     * @param Transaction $currentTransaction
     * @param Transaction[] $transactions
     * @return array
     */
    public function applyRule(Transaction $currentTransaction, array $transactions): Transaction
    {
        if (!$this->isTransactionApplied($currentTransaction)) {
            return $currentTransaction;
        }

        $currentMonthTransactions = $this->transactionProvider->getCurrentMonthCarrierAndSizeTransactions(
            $currentTransaction,
            self::LP_CARRIER_CODE,
            self::L_PACKAGE_SIZE,
        );

        foreach ($currentMonthTransactions as $count => $transaction) {
            if ($currentTransaction->equalsTo($transaction)
                && ($count + 1) === self::THIRD_MONTH
                && $this->isTransactionApplied($currentTransaction)
            ) {
                $currentTransaction->setDiscount($currentTransaction->getShippingPrice());
                $currentTransaction->setShippingPrice(self::FREE_SHIPPING_PRICE);

                return $currentTransaction;
            }
        }

        return $currentTransaction;
    }

    private function isTransactionApplied(Transaction $transaction): bool
    {
        if ($transaction->getCarrierCode() === self::LP_CARRIER_CODE
            && $transaction->getPackageSize()=== self::L_PACKAGE_SIZE
        ) {
            return true;
        }

        return false;
    }
}
