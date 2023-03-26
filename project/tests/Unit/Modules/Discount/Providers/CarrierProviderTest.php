<?php declare(strict_types=1);

namespace Tests\Unit\Modules\Discount\Providers;

use App\Models\Carrier;
use App\Modules\Discount\Interfaces\Providers\CarrierProviderInterface;
use App\Modules\Discount\Providers\CarrierProvider;
use App\Modules\File\Interfaces\Services\FileReaderServiceInterface;
use Illuminate\Contracts\Console\Kernel;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class CarrierProviderTest extends TestCase
{
    private MockObject|FileReaderServiceInterface $fileReaderService;

    private CarrierProviderInterface $carrierProvider;

    protected function setUp(): void
    {
        $this->fileReaderService = $this->createMock(FileReaderServiceInterface::class);
    }

    public function testGetAllCarriers(): void
    {
        $carriers = [
            $this->mockCarrier('Carrier A', 'M', 10.0),
            $this->mockCarrier('Carrier B', 'S', 5.0),
            $this->mockCarrier('Carrier C', 'L', 20.0),
        ];

        $this
            ->fileReaderService
            ->method('readCarriersFile')
            ->willReturn($carriers)
        ;

        $this->carrierProvider = new CarrierProvider($this->fileReaderService);

        $result = $this->carrierProvider->getAllCarriers();

        $this->assertEquals($carriers, $result);
    }

    public function testGetLowestPriceCarrier(): void
    {
        $carriers = [
            $this->mockCarrier('Carrier A', 'M', 10.0),
            $this->mockCarrier('Carrier B', 'S', 5.0),
            $this->mockCarrier('Carrier C', 'L', 20.0),
        ];

        $this
            ->fileReaderService
            ->method('readCarriersFile')
            ->willReturn($carriers)
        ;

        $this->carrierProvider = new CarrierProvider($this->fileReaderService);

        $result = $this->carrierProvider->getLowestPriceCarrier();

        $this->assertEquals($carriers[1], $result);
    }

    private function mockCarrier(?string $carrierCode, ?string $packageSize, ?float $price): Carrier
    {
        $carrier = new Carrier();

        $carrier->setCarrierCode($carrierCode);
        $carrier->setPackageSize($packageSize);
        $carrier->setPrice($price);

        return $carrier;
    }
}
