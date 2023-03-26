<?php declare(strict_types=1);

namespace Tests\Modules\File\Processors;

use App\Models\Carrier;
use App\Modules\File\Processors\CarrierProcessor;
use PHPUnit\Framework\TestCase;

class CarrierProcessorTest extends TestCase
{
    private CarrierProcessor $processor;

    public function setUp(): void
    {
        $this->processor = new CarrierProcessor();
    }

    /** @dataProvider provideTestProcessData */
    public function testProcessData(?string $carrierCode, ?string $packageSize, ?float $price): void
    {
        $input = [$carrierCode, $packageSize, $price];

        $carrier = new Carrier();

        $carrier->setCarrierCode($carrierCode);
        $carrier->setPackageSize($packageSize);
        $carrier->setPrice($price);

        $this->assertEquals($carrier,  $this->processor->processData($input));
    }

    private function provideTestProcessData(): array
    {
        return [
            'all values assigned' => ['FedEx', 'Small', 9.99],
            'some values nullable' => ['FedEx', null, null],
        ];
    }
}
