<?php
declare(strict_types=1);

namespace App\Entity;

class Expression
{
    /**
     * @param array $values
     * @param array $attributes
     * @return static
     * @throws \App\Exception\InvalidOperationException
     */
    public static function fromArray(array $values, array $attributes): self
    {
        if (!array_key_exists('a', $values) || !array_key_exists('b', $values)) {
            throw new \InvalidArgumentException();
        }

        if ($values['a'] === '' || $values['b'] === '') {
            throw new \InvalidArgumentException();
        }

        return new self(
            new Operator($values['fn']),
            new Argument($values['a'], $attributes),
            new Argument($values['b'], $attributes)
        );
    }

    /**
     * @param Operator $operator
     * @param Argument $argumentA
     * @param Argument $argumentB
     */
    private function __construct(
        private Operator $operator,
        private Argument $argumentA,
        private Argument $argumentB,
    ) {
    }

    /**
     * @return Operator
     */
    public function operator(): Operator
    {
        return $this->operator;
    }

    /**
     * @return Argument
     */
    public function argumentA(): Argument
    {
        return $this->argumentA;
    }

    /**
     * @return Argument
     */
    public function argumentB(): Argument
    {
        return $this->argumentB;
    }

    /**
     * @return bool
     */
    public function isAddition(): bool
    {
        return $this->operator->isAddition();
    }

    /**
     * @return bool
     */
    public function isDeletion(): bool
    {
        return $this->operator->isDeletion();
    }

    /**
     * @return bool
     */
    public function isDivision(): bool
    {
        return $this->operator->isDivision();
    }

    /**
     * @return bool
     */
    public function isMultiplication(): bool
    {
        return $this->operator->isMultiplication();
    }
}
