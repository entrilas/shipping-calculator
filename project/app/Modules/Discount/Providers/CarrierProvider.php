<?php declare(strict_types=1);

/**
 * CarrierProvider is responsible for providing access to providers data and filtering providers based on criteria.
 */

namespace App\Modules\Discount\Providers;

use App\Models\Carrier;
use App\Modules\Discount\Interfaces\Providers\CarrierProviderInterface;
use App\Modules\File\Interfaces\Services\FileReaderServiceInterface;

class CarrierProvider implements CarrierProviderInterface
{
    private const SMALL_PACKAGE_SIZE = 'S';
    private const PROVIDERS_PATH = '/var/www/html/storage/app/providers.txt';

    private array $carriers;

    public function __construct(
        private FileReaderServiceInterface $fileReaderService,
    ) {
        $this->carriers = $this->fileReaderService->readCarriersFile(self::PROVIDERS_PATH);
    }

    /**
     * @return Carrier[]
     */
    public function getAllCarriers(): array
    {
        return $this->carriers;
    }

    /**
     * @return Carrier
     */
    public function getLowestPriceCarrier(): Carrier
    {
        $price = reset($this->carriers)->getPrice();
        $currentCarrier = reset($this->carriers);

        foreach ($this->carriers as $carrier) {
            if ($carrier->getPackageSize() === self::SMALL_PACKAGE_SIZE && $carrier->getPrice() < $price) {
                $price = $carrier->getPrice();
                $currentCarrier = $carrier;
            }
        }

        return $currentCarrier;
    }
}
