<?php
declare(strict_types=1);

namespace App\Exception;

class InvalidOperationException extends \Exception
{
    public static function notAllowed(string $operation): self
    {
        return new self("Operation '$operation' not allowed");
    }
}
