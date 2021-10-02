<?php
declare(strict_types=1);

namespace App;

use App\Entity\Query;

interface QueryBuilderInterface
{
    public function fromString(string $string): Query;
}
