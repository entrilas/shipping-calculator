<?php

namespace App\Providers;

use App\Modules\Discount\Interfaces\Providers\CarrierProviderInterface;
use App\Modules\Discount\Interfaces\Providers\TransactionProviderInterface;
use App\Modules\Discount\Interfaces\Rules\RuleInterface;
use App\Modules\Discount\Interfaces\Services\DiscountServiceInterface;
use App\Modules\Discount\Interfaces\Transformer\TransactionTransformerInterface;
use App\Modules\Discount\Providers\CarrierProvider;
use App\Modules\Discount\Providers\TransactionProvider;
use App\Modules\Discount\Rules\ChainedRule;
use App\Modules\Discount\Rules\MonthlyLimitRule;
use App\Modules\Discount\Rules\SmallPackageRule;
use App\Modules\Discount\Rules\ThirdLargeShipmentRule;
use App\Modules\Discount\Services\DiscountService;
use App\Modules\Discount\Transformer\TransactionTransformer;
use App\Modules\File\Interfaces\FileReaderInterface;
use App\Modules\File\Interfaces\Processors\ProcessorInterface;
use App\Modules\File\Interfaces\Services\FileReaderServiceInterface;
use App\Modules\File\Processors\CarrierProcessor;
use App\Modules\File\Processors\TransactionProcessor;
use App\Modules\File\FileReader;
use App\Modules\File\Services\FileReaderService;
use App\Modules\Output\Interfaces\OutputServiceInterface;
use App\Modules\Output\OutputService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(FileReaderInterface::class, FileReader::class);
        $this->app->bind(ProcessorInterface::class, TransactionProcessor::class);
        $this->app->bind(ProcessorInterface::class, CarrierProcessor::class);
        $this->app->bind(OutputServiceInterface::class, OutputService::class);

        $this->app->bind('FileReaderInterface.transaction', function ($app) {
            $transactionProcessor = $app->make(TransactionProcessor::class);

            return $app->make(FileReaderInterface::class, ['processor' => $transactionProcessor]);
        });

        $this->app->bind('FileReaderInterface.provider', function ($app) {
            $providerProcessor = $app->make(CarrierProcessor::class);

            return $app->make(FileReaderInterface::class, ['processor' => $providerProcessor]);
        });

        $this->app->bind(FileReaderServiceInterface::class, function ($app) {
            $transactionFileReader = $app->make('FileReaderInterface.transaction');
            $providerFileReader = $app->make('FileReaderInterface.provider');

            return new FileReaderService($transactionFileReader, $providerFileReader);
        });

        $this->app->bind(DiscountServiceInterface::class, function ($app) {
            $chainedRules = new ChainedRule($app->make(RuleInterface::class));
            $transactionTransformer = $app->make(TransactionTransformerInterface::class);

            return new DiscountService(
                $chainedRules,
                $transactionTransformer,
                $app->make(CarrierProviderInterface::class),
                $app->make(TransactionProviderInterface::class),
            );
        });

        $this->app->bind(CarrierProviderInterface::class, function ($app) {
            return new CarrierProvider($app->make(FileReaderServiceInterface::class));
        });

        $this->app->bind(TransactionProviderInterface::class, function ($app) {
            return new TransactionProvider( $app->make(FileReaderServiceInterface::class));
        });

        $this->app->bind(RuleInterface::class, function ($app) {
            return new ChainedRule(
                new SmallPackageRule($app->make(CarrierProviderInterface::class)),
                new ThirdLargeShipmentRule($app->make(TransactionProviderInterface::class)),
                new MonthlyLimitRule($app->make(TransactionProviderInterface::class)),
            );
        });

        $this->app->bind(TransactionTransformerInterface::class, function ($app) {
            return new TransactionTransformer();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {

    }
}
