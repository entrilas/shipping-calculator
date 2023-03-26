<?php

namespace App\Console\Commands;

use App\Modules\Discount\Interfaces\Services\DiscountServiceInterface;
use App\Modules\Output\Interfaces\OutputServiceInterface;
use Illuminate\Console\Command;

class Discount extends Command
{
    public function __construct(
        private DiscountServiceInterface $discountService,
        private OutputServiceInterface $outputService,
    ) {
        parent::__construct();
    }

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:discount';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command calculates the discounts from the input.';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $transactions = $this->discountService->calculateDiscounts();

        $this->outputService->printConsole($transactions);
    }
}
