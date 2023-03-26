<?php declare(strict_types=1);

/**
 * The MonthlyLimitRule class is responsible for applying a monthly limit on the total discount given to a user's transactions.
 * It implements the RuleInterface and uses a TransactionProvider to get the current month's transactions for the user.
 * The applyRule() method calculates the current month's discounts and calls the recalculateDiscount() method to recalculate
 * the discount for the current transaction if the monthly limit has been exceeded. The recalculateDiscount() method recalculates
 * the discount and shipping price for the current transaction if the total discounts for the month exceeds the monthly limit.
 */

namespace App\Modules\Discount\Rules;

use App\Models\Transaction;
use App\Modules\Discount\Interfaces\Providers\TransactionProviderInterface;
use App\Modules\Discount\Interfaces\Rules\RuleInterface;

class MonthlyLimitRule implements RuleInterface
{
    private const MONTHLY_LIMIT = 10;

    public function __construct(
        private TransactionProviderInterface $transactionProvider,
    ) {
    }

    /**
     * @param Transaction $currentTransaction
     * @param Transaction[] $transactions
     * @return Transaction
     */
    public function applyRule(Transaction $currentTransaction, array $transactions): Transaction
    {
        $currentMonthTransactions = $this->transactionProvider
            ->getCurrentMonthTransactions($currentTransaction, $transactions);

        return $this->recalculateDiscount($currentTransaction, $currentMonthTransactions);
    }

    /**
     * @param Transaction $currentTransaction
     * @param Transaction[] $transactions
     * @return Transaction
     */
    public function recalculateDiscount(Transaction $currentTransaction, array $transactions): Transaction
    {
        $discounts = 0;

        foreach ($transactions as $transaction) {
            $discounts += $transaction->getDiscount();

            if ($currentTransaction->equalsTo($transaction) && $discounts >= self::MONTHLY_LIMIT) {
                $exceededDiscount = $discounts - self::MONTHLY_LIMIT;
                $originalPrice = ($currentTransaction->getShippingPrice() + $currentTransaction->getDiscount());
                $recalculatedDiscount = $currentTransaction->getDiscount() - $exceededDiscount;
                $recalculatedShippingPrice = $originalPrice - $recalculatedDiscount;

                $currentTransaction->setDiscount($recalculatedDiscount);
                $currentTransaction->setShippingPrice($recalculatedShippingPrice);

                return $currentTransaction;
            }
        }

        return $currentTransaction;
    }
}
