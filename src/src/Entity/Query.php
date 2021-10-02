<?php
declare(strict_types=1);

namespace App\Entity;

use App\Repository\FactRepository;

/**
 * Represents a valid DSQ query
 */
class Query
{
    /**
     * @param Security $security
     * @param Expression $expression
     */
    public function __construct(
        private Security $security,
        private Expression $expression,
    ) {
    }

    /**
     * @return Security
     */
    public function security(): Security
    {
        return $this->security;
    }

    /**
     * @return Expression
     */
    public function expression(): Expression
    {
        return $this->expression;
    }

    /**
     * @param FactRepository $repository
     * @return float
     * @throws \Exception
     */
    public function result(FactRepository $repository): float
    {
        return $this->expression->calculateForSecurity($this->security, $repository);
    }
}
