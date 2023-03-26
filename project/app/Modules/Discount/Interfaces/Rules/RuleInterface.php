<?php declare(strict_types=1);

namespace App\Modules\Discount\Interfaces\Rules;

use App\Models\Transaction;

interface RuleInterface
{
    public function applyRule(Transaction $transaction, array $transactions): Transaction;
}
