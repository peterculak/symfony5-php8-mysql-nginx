<?php

namespace App\Tests\Entity;

use App\Entity\Argument;
use App\Entity\Attribute;
use App\Entity\Expression;
use App\Entity\Fact;
use App\Entity\Security;
use App\Repository\FactRepository;
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


    public function testItCanCreateNestedExpression(): void
    {
        $input = '{"fn": "+", "a": {"fn": "+", "a": "eps", "b": "shares"}, "b": {"fn": "+", "a": "assets", "b": "999"}}';

        $decoded = json_decode($input, true);

        $expression = Expression::fromArray($decoded, self::ATTRIBUTES);

        $this->assertEquals('+', $expression->operator()->operation());

        $argumentA = $expression->argumentA();
        $argumentB = $expression->argumentB();
        $this->assertInstanceOf(Argument::class, $argumentA);
        $this->assertInstanceOf(Argument::class, $argumentB);
        $this->assertEquals('+', $expression->operator()->operation());
        $this->assertEquals('+', $expression->operator()->operation());

        $argumentAA = $argumentA->unwrap()->argumentA();
        $argumentAB = $argumentA->unwrap()->argumentB();
        $this->assertEquals('eps', $argumentAA->unwrap()->name());
        $this->assertEquals('shares', $argumentAB->unwrap()->name());

        $argumentBA = $argumentB->unwrap()->argumentA();
        $argumentBB = $argumentB->unwrap()->argumentB();
        $this->assertEquals('assets', $argumentBA->unwrap()->name());
        $this->assertEquals(999, $argumentBB->unwrap());
    }

    public function testItCalculatesResult(): void
    {
        $input = '{"fn": "+", "a": "1", "b": "2"}';
        $decoded = json_decode($input, true);
        $expression = Expression::fromArray($decoded, self::ATTRIBUTES);
        $factRepository = $this->createMock(FactRepository::class);
        $security = new Security(1, 'ABC');

        $this->assertEquals(3, $expression->calculateForSecurity($security, $factRepository));
    }

    public function testItCalculatesResultWhenNumberAndAttribute(): void
    {
        $input = '{"fn": "+", "a": "1.1", "b": "price"}';

        $decoded = json_decode($input, true);

        $expression = Expression::fromArray($decoded, self::ATTRIBUTES);

        $attributeId = 1;
        $securityId = 1;
        $security = new Security($securityId, 'ABC');
        $fact = new Fact(
            $security,
            new Attribute($attributeId, 'price'),
            2.2
        );
        $factRepository = $this->createMock(FactRepository::class);
        $factRepository->expects($this->any())
            ->method('findOneBy')
            ->with(['attribute' => $attributeId, 'security' => $securityId])
            ->willReturn($fact);

        $this->assertEquals(3.3, $expression->calculateForSecurity($security, $factRepository));
    }

    public function testItCalculatesResultWhenBothAttributes(): void
    {
        $input = '{"fn": "+", "a": "price", "b": "debt"}';

        $decoded = json_decode($input, true);

        $expression = Expression::fromArray($decoded, self::ATTRIBUTES);

        $securityId = 1;
        $security = new Security($securityId, 'ABC');

        $priceAttributeId = 1;
        $factPrice = new Fact(
            $security,
            new Attribute($priceAttributeId, 'price'),
            11.11
        );
        $factRepository = $this->createMock(FactRepository::class);

        $debtAttributeId = 9;
        $factDebt = new Fact(
            $security,
            new Attribute($debtAttributeId, 'debt'),
            22.22
        );

        $factRepository->expects($this->any())
            ->method('findOneBy')
            ->withConsecutive(
                [['attribute' => $priceAttributeId, 'security' => $securityId]],
                [['attribute' => $debtAttributeId, 'security' => $securityId]]
            )
            ->willReturnOnConsecutiveCalls($factPrice, $factDebt);

        $this->assertEquals(33.33, $expression->calculateForSecurity($security, $factRepository));
    }

    public function testItCalculatesResultForNestedExpression(): void
    {
        $input = '{"fn": "+", "a": {"fn": "+", "a": "eps", "b": "shares"}, "b": {"fn": "+", "a": "assets", "b": "liabilities"}}';
        $decoded = json_decode($input, true);
        $expression = Expression::fromArray($decoded, self::ATTRIBUTES);
        $factRepository = $this->createMock(FactRepository::class);
        $securityId = 1;
        $security = new Security($securityId, 'ABC');


        $epsId = self::ATTRIBUTES['eps'];
        $epsFact = new Fact(
            $security,
            new Attribute($epsId, 'eps'),
            1
        );
        $sharesId = self::ATTRIBUTES['shares'];
        $sharesFact = new Fact(
            $security,
            new Attribute($sharesId, 'shares'),
            2
        );
        $assetsId = self::ATTRIBUTES['assets'];
        $assetsFact = new Fact(
            $security,
            new Attribute($assetsId, 'assets'),
            3
        );
        $liabilitiesId = self::ATTRIBUTES['liabilities'];
        $liabilitiesFact = new Fact(
            $security,
            new Attribute($liabilitiesId, 'liabilities'),
            4
        );
        $factRepository->expects($this->any())
            ->method('findOneBy')
            ->withConsecutive(
                [['attribute' => $epsId, 'security' => $securityId]],
                [['attribute' => $sharesId, 'security' => $securityId]],
                [['attribute' => $assetsId, 'security' => $securityId]],
                [['attribute' => $liabilitiesId, 'security' => $securityId]],
            )
            ->willReturnOnConsecutiveCalls($epsFact, $sharesFact, $assetsFact, $liabilitiesFact);

        $this->assertEquals(10, $expression->calculateForSecurity($security, $factRepository));
    }
}
