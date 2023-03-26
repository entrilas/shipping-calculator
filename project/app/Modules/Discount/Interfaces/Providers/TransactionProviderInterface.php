<?php

namespace App\Modules\Discount\Interfaces\Providers;

use App\Models\Transaction;

interface TransactionProviderInterface
{
    public function getCurrentMonthTransactions(Transaction $currentTransaction, array $transactions): array;
    public function getCurrentMonthCarrierAndSizeTransactions(
        Transaction $currentTransaction,
        string $carrierCode,
        string $packageSize,
    ): array;
    public function getAllTransactions(): array;
}
