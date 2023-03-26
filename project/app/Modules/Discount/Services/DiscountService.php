<?php declare(strict_types=1);

/**
 * The DiscountService class is responsible for calculating discounts for a list of transactions based on a chain of
 * discount rules. The service retrieves the list of transactions and carriers from the provider interfaces, applies a
 * transaction transformation to set the correct shipment prices, and then applies the chain of discount rules to each
 * transaction. The final list of transactions with discounts applied is returned.
 */

namespace App\Modules\Discount\Services;

use App\Modules\Discount\Interfaces\Providers\CarrierProviderInterface;
use App\Modules\Discount\Interfaces\Providers\TransactionProviderInterface;
use App\Modules\Discount\Interfaces\Rules\RuleInterface;
use App\Modules\Discount\Interfaces\Services\DiscountServiceInterface;
use App\Modules\Discount\Interfaces\Transformer\TransactionTransformerInterface;

class DiscountService implements DiscountServiceInterface
{
    public function __construct(
        private RuleInterface $chainedRules,
        private TransactionTransformerInterface $transactionTransformer,
        private CarrierProviderInterface $carrierProvider,
        private TransactionProviderInterface $transactionProvider,
    ) {
    }

    /**
     * @return array
     */
    public function calculateDiscounts(): array
    {
        $transactions = $this->transactionProvider->getAllTransactions();
        $carriers = $this->carrierProvider->getAllCarriers();

        foreach ($transactions as &$transaction) {
            $transaction = $this->transactionTransformer->transformShipmentPrice($transaction, $carriers);
            $transaction = $this->chainedRules->applyRule($transaction, $transactions);
        }

        return $transactions;
    }
}
