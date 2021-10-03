<?php
declare(strict_types=1);

namespace App\Entity;

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
}
