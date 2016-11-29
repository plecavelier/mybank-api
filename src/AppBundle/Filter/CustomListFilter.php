<?php

namespace AppBundle\Filter;

use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\AbstractFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Util\QueryNameGeneratorInterface;
use ApiPlatform\Core\Exception\InvalidArgumentException;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\DBAL\Types\Type as DBALType;
use Doctrine\ORM\QueryBuilder;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\RequestStack;

class CustomListFilter extends AbstractFilter
{

    public function __construct(ManagerRegistry $managerRegistry, RequestStack $requestStack, LoggerInterface $logger = null, array $properties = null)
    {
        parent::__construct($managerRegistry, $requestStack, $logger, $properties);
    }

    /**
     * {@inheritdoc}
     */
    public function getDescription(string $resourceClass) : array
    {
        $description = [];

        $properties = $this->properties;
        if (null === $properties) {
            $properties = array_fill_keys($this->getClassMetadata($resourceClass)->getFieldNames(), null);
        }

        foreach ($properties as $property => $unused) {
            if (!$this->isPropertyMapped($property, $resourceClass)) {
                continue;
            }

            $description[$property] = [
                'property' => $property,
                'type' => 'string',
                'required' => false,
            ];
        }

        return $description;
    }

    /**
     * {@inheritdoc}
     */
    protected function filterProperty(string $property, $value, QueryBuilder $queryBuilder, QueryNameGeneratorInterface $queryNameGenerator, string $resourceClass, string $operationName = null)
    {
        if (
            !$this->isPropertyEnabled($property) ||
            !$this->isPropertyMapped($property, $resourceClass)
        ) {
            return;
        }

        $alias = 'o';
        $field = $property;

        if ($this->isPropertyNested($property)) {
            list($alias, $field) = $this->addJoinsForNestedProperty($property, $alias, $queryBuilder, $queryNameGenerator);
        }
        $valueParameter = $queryNameGenerator->generateParameterName($field);

        $values = explode(',', $value);
        $queryBuilder
            ->andWhere(sprintf('%s.%s IN (:%s)', $alias, $field, $valueParameter))
            ->setParameter($valueParameter, $values);
    }
}