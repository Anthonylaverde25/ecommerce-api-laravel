<?php
declare(strict_types=1);
namespace App\Domain\Product\ValueObjects;

use InvalidArgumentException;

final class ItemPrice
{
    private const MIN_PRICE = 0.00;
    
    public function __construct(private float $value)
    {
        $this->validate();
    }


    private function validate(): void
    {
        if($this->value < self::MIN_PRICE){
            throw new InvalidArgumentException(
                sprintf('Item price must be at least %.2f', self::MIN_PRICE)
            );
        }
    }


}
