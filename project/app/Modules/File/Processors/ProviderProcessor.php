<?php declare(strict_types=1);

namespace App\Modules\File\Processors;

use App\Models\Provider;
use App\Modules\File\Interfaces\Processors\ProcessorInterface;

class ProviderProcessor implements ProcessorInterface
{
    public function processData(array $values): Provider
    {
        $provider = $values[0] ?? null;
        $packageSize = $values[1] ?? null;
        $price = $values[2] ?? null;

        return new Provider($provider, $packageSize, $price);
    }
}
