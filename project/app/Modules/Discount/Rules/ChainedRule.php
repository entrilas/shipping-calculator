<?php declare(strict_types=1);

/**
 * I have chose to apply Chain of Responsibility design pattern in this case to apply three rules mentioned.
 * The ChainedRule class is a composite rule that applies a chain of other rules to a transaction object.
 * It implements the RuleInterface and its applyRule() method applies each rule in the chain to the transaction
 * in sequence. The transaction object is passed to each rule in the chain and is modified by each rule.
 * This class is used in the DiscountService to apply multiple rules to a single transaction in a flexible way.
 * In this way rules can be added and removed in very flexible way.
 */

namespace App\Modules\Discount\Rules;

use App\Models\Transaction;
use App\Modules\Discount\Interfaces\Rules\RuleInterface;

class ChainedRule implements RuleInterface
{
    private array $rules;

    public function __construct(
        RuleInterface ...$rules,
    ) {
        $this->rules = $rules;
    }

    /**
     * @param Transaction $transaction
     * @param Transaction[] $transactions
     * @return array
     */
    public function applyRule(Transaction $transaction, array $transactions): Transaction
    {
       foreach ($this->rules as $rule) {
           $transaction = $rule->applyRule($transaction, $transactions);
       }

       return $transaction;
    }
}
