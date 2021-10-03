<?php

namespace App\Tests\UseCase;

use App\QueryBuilder;
use App\UseCase\EvaluateDslQuery;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class EvaluateDslQueryTest extends KernelTestCase
{
    public function testItExecutesUseCaseAndGetsResult(): void
    {
        $kernel = self::bootKernel();

        $container = static::getContainer();

        $useCase = $container->get(EvaluateDslQuery::class);
        $queryBuilder = $container->get(QueryBuilder::class);
        $query = $queryBuilder->fromString('{"expression": {"fn": "+", "a": "1", "b": 2},"security": "ABC"}');
        $result = $useCase->execute($query);

        $this->assertEquals(3, $result);
    }

    public function testItExecutesUseCaseAndGetsResultWhenNumberAndAttribute(): void
    {
        $kernel = self::bootKernel();

        $container = static::getContainer();

        $useCase = $container->get(EvaluateDslQuery::class);
        $queryBuilder = $container->get(QueryBuilder::class);
        $query = $queryBuilder->fromString('{"expression": {"fn": "+", "a": "1.1", "b": "price"},"security": "ABC"}');
        $result = $useCase->execute($query);

        $this->assertEquals(2.1, $result);
    }

    public function testItExecutesUseCaseAndGetsResultWhenBothAttributes(): void
    {
        $kernel = self::bootKernel();

        $container = static::getContainer();

        $useCase = $container->get(EvaluateDslQuery::class);
        $queryBuilder = $container->get(QueryBuilder::class);
        $query = $queryBuilder->fromString('{"expression": {"fn": "+", "a": "price", "b": "debt"},"security": "ABC"}');
        $result = $useCase->execute($query);

        $this->assertEquals(10, $result);
    }

    public function testItExecutesUseCaseAndGetsResultForNestedExpression(): void
    {
        $kernel = self::bootKernel();

        $container = static::getContainer();

        $useCase = $container->get(EvaluateDslQuery::class);
        $queryBuilder = $container->get(QueryBuilder::class);
        $query = $queryBuilder->fromString('{"expression": {"fn": "+", "a": {"fn": "+", "a": "eps", "b": "shares"}, "b": {"fn": "+", "a": "assets", "b": "liabilities"}},"security": "ABC"}');
        $result = $useCase->execute($query);

        $this->assertEquals(27, $result);
    }
}
