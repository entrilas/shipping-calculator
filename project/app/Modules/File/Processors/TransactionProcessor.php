<?php declare(strict_types=1);

namespace App\Modules\File\Processors;

use App\Models\Transaction;
use App\Modules\File\Interfaces\Processors\ProcessorInterface;
use DateTime;

class TransactionProcessor implements ProcessorInterface
{
    public function processData(array $values): object
    {
        $transactionDate = new DateTime($values[0]) ?? null;
        $packageSize = $values[1] ?? null;
        $carrierCode = $values[2] ?? null;

        return new Transaction($transactionDate, $packageSize, $carrierCode);
    }
}
