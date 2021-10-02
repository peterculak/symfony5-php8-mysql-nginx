<?php

namespace App\Tests\Entity;

use App\Entity\Argument;
use PHPUnit\Framework\TestCase;

class ArgumentTest extends TestCase
{
    private const ATTRIBUTES = [
        'price' => 1,
        'eps' => 2,
        'dps' => 3,
        'sales' => 4,
        'ebitda' => 5,
        'free_cash_flow' => 6,
        'assets' => 7,
        'liabilities' => 8,
        'debt' => 9,
        'shares' => 10,
    ];

    public function testItUnwrapsArgumentWhenFloat(): void
    {
        $argument = new Argument(1.2, self::ATTRIBUTES);
        $this->assertEquals(1.2, $argument->unwrap());
    }

    public function testItUnwrapsArgumentWhenAttribute(): void
    {
        $argument = new Argument(
            'price',
            self::ATTRIBUTES
        );
        $this->assertEquals(1, $argument->unwrap()->getId());

        $argument = new Argument(
            'shares',
            self::ATTRIBUTES
        );
        $this->assertEquals(10, $argument->unwrap()->getId());
    }

    public function testItUnwrapsArgumentWhenExpression(): void
    {
        $expression = json_decode('{"fn": "+", "a": "1", "b": "2"}', true);

        $argument = new Argument(
            $expression,
            self::ATTRIBUTES
        );
        $this->assertTrue($argument->unwrap()->isAddition());
    }
}
