<?php
declare(strict_types=1);

namespace App;

use App\Entity\Expression;
use App\Entity\Query;
use App\Exception\InvalidOperationException;
use App\Exception\QueryException;
use App\Repository\AttributeRepository;
use App\Repository\SecurityRepository;

/**
 * Builds a valid DSL query
 */
class QueryBuilder implements QueryBuilderInterface
{
    /**
     * @param SecurityRepository $securityRepository
     * @param AttributeRepository $attributeRepository
     */
    public function __construct(
        private SecurityRepository $securityRepository,
        private AttributeRepository $attributeRepository
    ) {
    }

    /**
     * @param string $string
     * @return Query
     * @throws InvalidOperationException
     * @throws QueryException
     */
    public function fromString(string $string): Query
    {
        try {
            $decoded = json_decode($string, true);
        } catch (\Exception $e) {
            throw QueryException::malformedJson();
        }

        if (!array_key_exists('security', $decoded)) {
            throw QueryException::missingKey('security');
        }

        if (!array_key_exists('expression', $decoded)) {
            throw QueryException::missingKey('expression');
        }

        $attributes = [];

        foreach ($this->attributeRepository->findAll() as $key => $attribute) {
            $attributes[$attribute->name()] = $attribute->getId();
        }

        $security = $this->securityRepository->findOneBy(['symbol' => $decoded['security']]);

        if (!$security) {
            throw QueryException::securitySymbolNotExist($decoded['security']);
        }

        return new Query(
            $security,
            Expression::fromArray($decoded['expression'], $attributes)
        );
    }
}
