<?php

namespace App\Exceptions;

use Exception;

class InvalidPathException extends Exception
{
    public function __toString(): string
    {
        return $this->getMessage() . PHP_EOL;
    }
}
