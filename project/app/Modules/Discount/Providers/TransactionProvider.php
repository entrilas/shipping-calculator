<?php declare(strict_types=1);

/**
 * TransactionProvider is responsible for providing access to transaction data and filtering transactions based on criteria.
 */

namespace App\Modules\Discount\Providers;

use App\Models\Transaction;
use App\Modules\Discount\Interfaces\Providers\TransactionProviderInterface;
use App\Modules\File\Interfaces\Services\FileReaderServiceInterface;

class TransactionProvider implements TransactionProviderInterface
{
    private const TRANSACTIONS_PATH = __DIR__ . '/../../../../storage/app/input.txt';

    private array $transactions;

    public function __construct(
        private FileReaderServiceInterface $fileReaderService,
    ) {
        $this->transactions = $this->fileReaderService->readTransactionsFile(self::TRANSACTIONS_PATH);
    }

    /**
     * @return Transaction[]
     */
    public function getAllTransactions(): array
    {
        return $this->transactions;
    }

    /**
     * @param Transaction $currentTransaction
     * @return array
     */
    public function getCurrentMonthTransactions(Transaction $currentTransaction, array $transactions = null): array
    {
        $filteredTransactions = [];
        $transactions ??= $this->transactions;

        foreach ($transactions as $transaction) {
            if ($currentTransaction->isCurrentMonth($transaction->getTransactionDateTime())) {
                $filteredTransactions[] = $transaction;
            }
        }

        return $filteredTransactions;
    }

    /**
     * @param Transaction $currentTransaction
     * @param string $carrierCode
     * @param string $packageSize
     * @return array
     */
    public function getCurrentMonthCarrierAndSizeTransactions(
        Transaction $currentTransaction,
        string $carrierCode,
        string $packageSize,
    ): array {
        $filteredTransactions = [];
        $transactions ??= $this->transactions;

        foreach ($transactions as $transaction) {
            if ($transaction->isCurrentMonth($currentTransaction->getTransactionDateTime())
                && ($transaction->getCarrierCode() === $carrierCode)
                && ($transaction->getPackageSize() === $packageSize)
            ) {
                $filteredTransactions[] = $transaction;
            }
        }

        return $filteredTransactions;
    }
}
