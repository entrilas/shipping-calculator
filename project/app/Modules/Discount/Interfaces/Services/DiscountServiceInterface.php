<?php declare(strict_types=1);

namespace App\Modules\Discount\Interfaces\Services;

interface DiscountServiceInterface
{
    public function calculateDiscounts(): array;
}
