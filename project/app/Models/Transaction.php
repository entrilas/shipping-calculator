<?php declare(strict_types=1);

namespace App\Models;

use DateTime;

class Transaction
{
    public function __construct(
        private readonly DateTime|null $transactionDate,
        private readonly string|null $packageSize,
        private readonly string|null $carrierCode,
    ) {
    }

    public function getTransactionDate(): ?DateTime
    {
        return $this->transactionDate;
    }

    public function getPackageSize(): ?string
    {
        return $this->packageSize;
    }

    public function getCarrierCode(): ?string
    {
        return $this->carrierCode;
    }
}
