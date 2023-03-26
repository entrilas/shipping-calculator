<?php declare(strict_types=1);

namespace App\Modules\Discount\Interfaces\Transformer;

use App\Models\Transaction;

interface TransactionTransformerInterface
{
    public function transformShipmentPrice(Transaction $transaction, array $providers): Transaction;
}
