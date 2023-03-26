<?php declare(strict_types=1);

namespace App\Models;

class Carrier
{
    private ?string $carrierCode;
    private ?string $packageSize;
    private ?float $price;

    public function setCarrierCode(?string $carrierCode): void
    {
        $this->carrierCode = $carrierCode;
    }

    public function setPackageSize(?string $packageSize): void
    {
        $this->packageSize = $packageSize;
    }

    public function setPrice(?float $price): void
    {
        $this->price = $price;
    }

    public function getCarrierCode(): ?string
    {
        return $this->carrierCode;
    }

    public function getPackageSize(): ?string
    {
        return $this->packageSize;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }
}
