<?php

/**
 * TransactionTransformer class is responsible for setting the shipment price of a transaction
 * by matching the transaction's carrier code and package size with the corresponding carrier's
 * price using an array of providers.
 */

namespace App\Modules\Discount\Transformer;

use App\Models\Transaction;
use App\Modules\Discount\Interfaces\Transformer\TransactionTransformerInterface;

class TransactionTransformer implements TransactionTransformerInterface
{
    public function setTransactionShipmentPrice(Transaction $transaction, array $providers): Transaction
    {
        $transactionShipmentPrice = $this->getTransactionShipmentPrice($transaction, $providers);
        $transaction->setShippingPrice($transactionShipmentPrice);

        return $transaction;
    }

    private function getTransactionShipmentPrice(Transaction $transaction, array $providers): float
    {
        foreach ($providers as $provider) {
            if ($provider->getCarrierCode() === $transaction->getCarrierCode()
                && $provider->getPackageSize() === $transaction->getPackageSize()
            ) {
                return $provider->getPrice();
            }
        }

        return reset($providers)->getPrice();
    }
}
