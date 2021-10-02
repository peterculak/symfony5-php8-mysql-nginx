<?php
declare(strict_types=1);

namespace App\Entity;

use App\Repository\FactRepository;

class Expression
{
    /**
     * @var Operator
     */
    private Operator $operator;

    /**
     * @var Argument
     */
    private Argument $argumentA;

    /**
     * @var Argument
     */
    private Argument $argumentB;

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

        $argumentA = new Argument($values['a'], $attributes);
        $argumentB = new Argument($values['b'], $attributes);

        return new self(
            new Operator($values['fn']),
            $argumentA,
            $argumentB
        );
    }

    /**
     * @param Operator $operator
     * @param Argument $argumentA
     * @param Argument $argumentB
     */
    private function __construct(
        Operator $operator,
        Argument $argumentA,
        Argument $argumentB,
    ) {
        $this->operator = $operator;
        $this->argumentA = $argumentA;
        $this->argumentB = $argumentB;
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
     * @param Security $security
     * @param FactRepository $factRepository
     * @return float
     * @throws \Exception
     */
    public function calculateForSecurity(Security $security, FactRepository $factRepository): float
    {
        $argumentValueA = $this->argumentA->getArgumentValue($security, $factRepository);
        $argumentValueB = $this->argumentB->getArgumentValue($security, $factRepository);

        if ($this->operator->isAddition()) {
            return $argumentValueA + $argumentValueB;
        } else if ($this->operator->isDeletion()) {
            return $argumentValueA - $argumentValueB;
        } else if ($this->operator->isDivision()) {
            return $argumentValueA / $argumentValueB;
        } else if ($this->operator->isMultiplication()) {
            return $argumentValueA * $argumentValueB;
        }

        throw \LogicException('Should never reach this');
    }
}
