<?php

namespace App\Tests\Entity;

use App\Entity\Argument;
use App\Entity\Expression;
use PHPUnit\Framework\TestCase;

class ExpressionTest extends TestCase
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

    public function testItCanCreateNestedExpressionAddition(): void
    {
        $input = '{"fn": "+", "a": {"fn": "+", "a": "eps", "b": "shares"}, "b": {"fn": "-", "a": "assets", "b": "999"}}';
        $decoded = json_decode($input, true);
        $expression = Expression::fromArray($decoded, self::ATTRIBUTES);

        $this->assertInstanceOf(Argument::class, $expression->argumentA());
        $this->assertInstanceOf(Argument::class, $expression->argumentB());
        $this->assertTrue($expression->isAddition());
        $this->assertFalse($expression->isDeletion());
        $this->assertFalse($expression->isMultiplication());
        $this->assertFalse($expression->isDivision());
    }

    public function testItCanCreateNestedExpressionDeletion(): void
    {
        $input = '{"fn": "-", "a": {"fn": "+", "a": "eps", "b": "shares"}, "b": {"fn": "-", "a": "assets", "b": "999"}}';
        $decoded = json_decode($input, true);
        $expression = Expression::fromArray($decoded, self::ATTRIBUTES);

        $this->assertInstanceOf(Argument::class, $expression->argumentA());
        $this->assertInstanceOf(Argument::class, $expression->argumentB());
        $this->assertFalse($expression->isAddition());
        $this->assertTrue($expression->isDeletion());
        $this->assertFalse($expression->isMultiplication());
        $this->assertFalse($expression->isDivision());
    }

    public function testItCanCreateNestedExpressionDivision(): void
    {
        $input = '{"fn": "/", "a": {"fn": "+", "a": "eps", "b": "shares"}, "b": {"fn": "-", "a": "assets", "b": "999"}}';
        $decoded = json_decode($input, true);
        $expression = Expression::fromArray($decoded, self::ATTRIBUTES);

        $this->assertInstanceOf(Argument::class, $expression->argumentA());
        $this->assertInstanceOf(Argument::class, $expression->argumentB());
        $this->assertFalse($expression->isAddition());
        $this->assertFalse($expression->isDeletion());
        $this->assertFalse($expression->isMultiplication());
        $this->assertTrue($expression->isDivision());
    }

    public function testItCanCreateNestedExpressionMultiplication(): void
    {
        $input = '{"fn": "*", "a": {"fn": "+", "a": "eps", "b": "shares"}, "b": {"fn": "-", "a": "assets", "b": "999"}}';
        $decoded = json_decode($input, true);
        $expression = Expression::fromArray($decoded, self::ATTRIBUTES);

        $this->assertInstanceOf(Argument::class, $expression->argumentA());
        $this->assertInstanceOf(Argument::class, $expression->argumentB());
        $this->assertFalse($expression->isAddition());
        $this->assertFalse($expression->isDeletion());
        $this->assertTrue($expression->isMultiplication());
        $this->assertFalse($expression->isDivision());
    }
}
