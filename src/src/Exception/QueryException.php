<?php
declare(strict_types=1);

namespace App\Exception;

class QueryException extends \Exception
{
    public static function malformedJson(): self
    {
        return new self('Provided json is not a valid json string');
    }

    public static function missingKey(string $key): self
    {
        return new self("Key '$key' is missing in query");
    }

    public static function securitySymbolNotExist(string $symbol): self
    {
        return new self("Security symbol '$symbol' does not exist");
    }
}
