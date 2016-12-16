<?php

namespace AppBundle\Filter;

use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\FilterInterface;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Util\QueryNameGeneratorInterface;
use Doctrine\ORM\QueryBuilder;

class OperationChartDataFilter implements FilterInterface
{

    public function getDescription(string $resourceClass): array {
        return [
            'period' => [
                'type' => 'string',
                'required' => false
            ],
            'accounts' => [
                'type' => 'string',
                'required' => false
            ],
            'tags' => [
                'type' => 'string',
                'required' => false
            ]
        ];
    }

    public function apply(QueryBuilder $queryBuilder, QueryNameGeneratorInterface $queryNameGenerator, string $resourceClass, string $operationName = null) {

    }
}