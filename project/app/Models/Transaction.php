<?php declare(strict_types=1);

namespace App\Models;

use DateTime;

class Transaction
{
    private ?string $transactionDate = null;
    private ?string $packageSize = null;
    private ?string $carrierCode = null;
    private ?float $shippingPrice = null;
    private ?float $discount = null;

    public function setTransactionDate(?string $date): void
    {
        $this->transactionDate = $date;
    }

    public function setPackageSize(?string $packageSize): void
    {
        $this->packageSize = $packageSize;
    }

    public function setCarrierCode(?string $carrierCode): void
    {
        $this->carrierCode = $carrierCode;
    }

    public function setShippingPrice(?float $shippingPrice): void
    {
        $this->shippingPrice = !empty($shippingPrice) ? round($shippingPrice, 1) : $shippingPrice;
    }

    public function setDiscount(?float $discount): void
    {
        $this->discount = !empty($discount) ? round($discount, 1) : $discount;
    }

    public function getTransactionDate(): ?string
    {
        return $this->transactionDate;
    }

    public function getTransactionDateTime(): ?DateTime
    {
        if (!$this->transactionDate) {
            return null;
        }

        return new DateTime($this->transactionDate);
    }

    public function getPackageSize(): ?string
    {
        return $this->packageSize;
    }

    public function getCarrierCode(): ?string
    {
        return $this->carrierCode;
    }

    public function getShippingPrice(): ?float
    {
        return $this->shippingPrice;
    }

    public function getDiscount(): ?float
    {
        return $this->discount;
    }

    public function isCurrentMonth(DateTime $comparableDate): bool
    {
        $transactionDateMonth = $this->getTransactionDate() ? $this->getTransactionDateTime()->format('m') : null;
        $comparableDateMonth = $comparableDate->format('m');

        if ($transactionDateMonth === $comparableDateMonth) {
            return true;
        }

        return false;
    }

    public function equalsTo(Transaction $transaction): bool
    {
        if ($this->carrierCode === $transaction->getCarrierCode()
            && $this->transactionDate === $transaction->getTransactionDate()
            && $this->packageSize === $transaction->getPackageSize()
        ) {
            return true;
        }

        return false;
    }

    public function toString(): ?string
    {
        if (!$this->transactionDate || !$this->packageSize || !$this->carrierCode) {
            return sprintf('%s %s %s %s', $this->getTransactionDate(), $this->getPackageSize(), $this->getCarrierCode(), 'Ignored');
        }

        $discount = !$this->discount ? '-' : $this->discount;

        return sprintf(
            '%s %s %s %s %s',
            $this->transactionDate,
            $this->packageSize,
            $this->carrierCode,
            $this->shippingPrice,
            $discount,
        );
    }
}
