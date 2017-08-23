<?php

namespace AppBundle\Filter;

use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Util\QueryNameGeneratorInterface;
use AppBundle\Entity\Product;
use Doctrine\Common\Proxy\Exception\InvalidArgumentException;
use Doctrine\ORM\QueryBuilder;
use Symfony\Component\HttpFoundation\Request;

final class FulltextSearchFilter extends SearchFilter
{
    protected static $filterName = 'global_fulltext';

    /**
     * @inheritdoc
     */
    public function apply(QueryBuilder $queryBuilder, QueryNameGeneratorInterface $queryNameGenerator, string $resourceClass, string $operationName = null)
    {
        $request = $this->requestStack->getCurrentRequest();
        if (null === $request) {
            return;
        }

        if (!($this->isResourceClassValid($resourceClass))) {
            return;
        }

        $fulltext = $this->extractFilterValue($request);
        if (!$fulltext) {
            return;
        }

        $queryBuilder
            ->orWhere($queryBuilder->expr()->like('o.name', ':' . self::$filterName))
            ->orWhere($queryBuilder->expr()->like('o.description', ':' . self::$filterName))
            ->setParameter(self::$filterName, '%' . $fulltext . '%');

    }

    public function getDescription(string $resourceClass): array
    {
        $description[self::$filterName] = [
            'type' => 'string',
            'property' => 'name',
            'required' => false,
        ];

        return $description;
    }

    /**
     * @param Request $request
     * @return string
     */
    protected function extractFilterValue(Request $request)
    {
        return mb_strtolower($request->query->get(self::$filterName, '0'));
    }

    /**
     * @param $resourceClass
     * @return bool
     */
    protected function isResourceClassValid($resourceClass) {
        if ($resourceClass && $resourceClass == Product::class) {
            return true;
        }

        $this->logger->notice('Invalid resource class in filter configuration', [
            'exception' => new InvalidArgumentException(sprintf('Invalid resource class in filter configuration in "%s": "%s" is set, "%s" was expected.', self::class, $resourceClass, Product::class)),
        ]);

        return false;
    }
}