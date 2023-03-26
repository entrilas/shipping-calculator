<?php declare(strict_types=1);

namespace App\Modules\Output\Interfaces;

interface OutputServiceInterface
{
    public function printConsole(array $transactions): void;
}
