<?php

namespace App\Modules\Discount\Interfaces\Providers;

use App\Models\Carrier;

interface CarrierProviderInterface
{
    public function getLowestPriceCarrier(): Carrier;
    public function getAllCarriers(): array;
}
