<?php
declare(strict_types=1);

namespace App\UseCase;

use App\Entity\Attribute;
use App\Entity\Security;
use App\Entity\Query;
use App\Entity\Expression;
use App\Entity\Argument;
use App\Repository\FactRepository;

class EvaluateDslQuery implements EvaluateDsqlQueryInterface
{
    public function __construct(private FactRepository $repository)
    {

    }

    /**
     * @param Query $query
     * @return float
     * @throws \App\Exception\InvalidOperationException
     */
    public function execute(Query $query): float
    {
        return $this->calculate($query);
    }

    /**
     * @param Query $query
     * @return float
     * @throws \App\Exception\InvalidOperationException
     */
    private function calculate(Query $query): float
    {
        return $this->evaluateExpressionForSecurity(
            $query->expression(),
            $query->security(),
        );
    }

    /**
     * @param Expression $expression
     * @param Security $security
     * @return float
     * @throws \App\Exception\InvalidOperationException
     */
    private function evaluateExpressionForSecurity(Expression $expression, Security $security): float
    {
        $argumentValueA = $this->getArgumentValue($security, $expression->argumentA());
        $argumentValueB = $this->getArgumentValue($security, $expression->argumentB());

        if ($expression->isAddition()) {
            return $argumentValueA + $argumentValueB;
        } else if ($expression->isDeletion()) {
            return $argumentValueA - $argumentValueB;
        } else if ($expression->isDivision()) {
            return $argumentValueA / $argumentValueB;
        } else if ($expression->isMultiplication()) {
            return $argumentValueA * $argumentValueB;
        }

        throw new \LogicException('Should never reach this');
    }

    /**
     * @param Security $security
     * @param Argument $argument
     * @return float
     * @throws \App\Exception\InvalidOperationException
     */
    private function getArgumentValue(
        Security $security,
        Argument $argument,
    ): float {
        $unwrapped = $argument->unwrap();

        if (is_float($unwrapped)) {
            return $unwrapped;
        } else {
            if ($unwrapped instanceof Attribute) {
                $fact = $this->repository->findOneBy([
                    'attribute' => $unwrapped->getId(),
                    'security' => $security->getId()
                ]);

                return $fact->getValue();
            } else {
                return $this->evaluateExpressionForSecurity($unwrapped, $security);
            }
        }
    }
}