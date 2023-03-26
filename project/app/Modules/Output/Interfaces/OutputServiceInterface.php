<?php

namespace App\Modules\Output\Interfaces;

interface OutputServiceInterface
{
    public function printConsole(array $transactions): void;
}
