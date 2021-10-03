<?php
declare(strict_types=1);

namespace App\Entity;

use App\Exception\InvalidOperationException;

class Operator
{
    private const ADDITION = '+';
    private const DELETION = '-';
    private const DIVISION = '/';
    private const MULTIPLICATION = '*';

    private const ALLOWED_OPERATIONS = [self::ADDITION, self::DELETION, self::DIVISION, self::MULTIPLICATION];

    /**
     * @param string $operation
     * @throws InvalidOperationException
     */
    public function __construct(
        private string $operation,
    ) {
        if (!in_array($this->operation, self::ALLOWED_OPERATIONS, true)) {
            throw InvalidOperationException::notAllowed($this->operation);
        }
    }

    /**
     * @return bool
     */
    public function isAddition(): bool
    {
        return $this->operation === self::ADDITION;
    }

    /**
     * @return bool
     */
    public function isDeletion(): bool
    {
        return $this->operation === self::DELETION;
    }

    /**
     * @return bool
     */
    public function isDivision(): bool
    {
        return $this->operation === self::DIVISION;
    }

    /**
     * @return bool
     */
    public function isMultiplication(): bool
    {
        return $this->operation === self::MULTIPLICATION;
    }
}
