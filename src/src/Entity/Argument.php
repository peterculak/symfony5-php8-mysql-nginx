<?php
declare(strict_types=1);

namespace App\Entity;

/**
 * Represents an expression argument
 */
class Argument
{
    /**
     * @param float|int|string|array $value
     * @param array $attributes
     */
    public function __construct(
        private float|int|string|array $value,
        private array $attributes,
    ) {
    }

    /**
     * @return float|Attribute|Expression
     * @throws \App\Exception\InvalidOperationException
     */
    public function unwrap(): float|Attribute|Expression
    {
        if (is_array($this->value)) {
            //expression
            return Expression::fromArray($this->value, $this->attributes);
        } else {
            if (is_numeric($this->value)) {
                //number
                return (float)$this->value;
            } else {
                //attribute
                return new Attribute($this->getAttributeId($this->value), $this->value);
            }
        }
    }

    /**
     * @param string $attributeName
     * @return int
     */
    private function getAttributeId(string $attributeName): int
    {
        if (!array_key_exists($attributeName, $this->attributes)) {
            throw new \InvalidArgumentException("Invalid attribute name '$attributeName'");
        }

        return $this->attributes[$attributeName];
    }
}
