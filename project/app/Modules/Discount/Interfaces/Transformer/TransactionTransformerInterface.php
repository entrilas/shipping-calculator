<?php

namespace App\Modules\Discount\Interfaces\Transformer;

use App\Models\Transaction;

interface TransactionTransformerInterface
{
    public function setTransactionShipmentPrice(Transaction $transaction, array $providers): Transaction;
}
