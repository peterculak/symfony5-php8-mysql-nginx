<?php
declare(strict_types=1);

namespace App\UseCase;

use App\Entity\Query;

interface EvaluateDsqlQueryInterface
{
    public function execute(Query $query): float;
}
