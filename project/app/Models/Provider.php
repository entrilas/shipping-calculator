<?php declare(strict_types=1);

namespace App\Models;

class Provider
{
    public function __construct(
        private readonly string|null $provider,
        private readonly string|null $packageSize,
        private readonly float|null $price,
    ) {
    }

    public function getProvider(): ?string
    {
        return $this->provider;
    }

    public function getPackageSize(): ?string
    {
        return $this->packageSize;
    }

    public function getPrice(): ?string
    {
        return $this->price;
    }
}
