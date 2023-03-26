<?php declare(strict_types=1);

/**
 * This class is responsible for processing data from a carrier file into Carrier model objects.
 */

namespace App\Modules\File\Processors;

use App\Models\Carrier;
use App\Modules\File\Interfaces\Processors\ProcessorInterface;

class CarrierProcessor implements ProcessorInterface
{
    public function processData(array $values): Carrier
    {
        $carrierCode = $values[0] ?? null;
        $packageSize = $values[1] ?? null;
        $price = (float)$values[2] ?? null;

        $carrier = new Carrier();

        $carrier->setCarrierCode($carrierCode);
        $carrier->setPackageSize($packageSize);
        $carrier->setPrice($price);

        return $carrier;
    }
}
