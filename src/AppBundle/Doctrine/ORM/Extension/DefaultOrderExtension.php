<?php

namespace AppBundle\Doctrine\ORM\Extension;

use ApiPlatform\Core\Bridge\Doctrine\Orm\Extension\QueryCollectionExtensionInterface;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Extension\QueryItemExtensionInterface;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Util\QueryNameGeneratorInterface;
use ApiPlatform\Core\Metadata\Property\Factory\PropertyMetadataFactoryInterface;
use ApiPlatform\Core\Metadata\Property\Factory\PropertyNameCollectionFactoryInterface;
use AppBundle\Entity\Account;
use AppBundle\Entity\Tag;
use AppBundle\Entity\Operation;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Query\Expr;

final class DefaultOrderExtension implements QueryCollectionExtensionInterface
{

    /**
     * {@inheritdoc}
     */
    public function applyToCollection(QueryBuilder $queryBuilder, QueryNameGeneratorInterface $queryNameGenerator, string $resourceClass, string $operationName = null)
    {
        switch ($resourceClass) {
            case Tag::class:
                $rootAlias = $queryBuilder->getRootAliases()[0];
                $queryBuilder->addOrderBy(sprintf('%s.name', $rootAlias), 'ASC');
                $queryBuilder->addOrderBy(sprintf('%s.id', $rootAlias), 'ASC');
                break;

            case Account::class:
                $rootAlias = $queryBuilder->getRootAliases()[0];
                $queryBuilder->addOrderBy(sprintf('%s.id', $rootAlias), 'ASC');
                break;

            case Operation::class:
                $rootAlias = $queryBuilder->getRootAliases()[0];
                $queryBuilder->addOrderBy(sprintf('%s.id', $rootAlias), 'ASC');
                break;
        }
    }
}

