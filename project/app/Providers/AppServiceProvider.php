<?php

namespace App\Providers;

use App\Modules\File\Interfaces\FileReaderInterface;
use App\Modules\File\Interfaces\Processors\ProcessorInterface;
use App\Modules\File\Interfaces\Services\FileReaderServiceInterface;
use App\Modules\File\Processors\ProviderProcessor;
use App\Modules\File\Processors\TransactionProcessor;
use App\Modules\File\FileReader;
use App\Modules\File\Services\FileReaderService;
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
        $this->app->bind(ProcessorInterface::class, ProviderProcessor::class);

        $this->app->bind('FileReaderInterface.transaction', function ($app) {
            $transactionProcessor = $app->make(TransactionProcessor::class);

            return $app->make(FileReaderInterface::class, ['processor' => $transactionProcessor]);
        });

        $this->app->bind('FileReaderInterface.provider', function ($app) {
            $providerProcessor = $app->make(ProviderProcessor::class);

            return $app->make(FileReaderInterface::class, ['processor' => $providerProcessor]);
        });

        $this->app->bind(FileReaderServiceInterface::class, function ($app) {
            $transactionFileReader = $app->make('FileReaderInterface.transaction');
            $providerFileReader = $app->make('FileReaderInterface.provider');

            return new FileReaderService($transactionFileReader, $providerFileReader);
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
