<?php declare(strict_types=1);

namespace App\Modules\Output;

use App\Modules\Output\Interfaces\OutputServiceInterface;

class OutputService implements OutputServiceInterface
{
    public function printConsole(array $transactions): void
    {
        foreach ($transactions as $transaction) {
            echo $transaction->toString() . PHP_EOL;
        }
    }
}
