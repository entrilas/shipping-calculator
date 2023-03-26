<?php declare(strict_types=1);

/**
 * This class is responsible for processing data from a transactions file into Transaction model objects.
 */

namespace App\Modules\File\Processors;

use App\Models\Transaction;
use App\Modules\File\Interfaces\Processors\ProcessorInterface;

class TransactionProcessor implements ProcessorInterface
{
    public function processData(array $values): object
    {
        $transactionDate = $values[0] ?? null;
        $packageSize = $values[1] ?? null;
        $carrierCode = $values[2] ?? null;

        $transaction = new Transaction();

        $transaction->setTransactionDate($transactionDate);
        $transaction->setPackageSize($packageSize);
        $transaction->setCarrierCode($carrierCode);

        return $transaction;
    }
}
