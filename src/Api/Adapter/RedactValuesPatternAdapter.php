<?php
namespace RedactValues\Api\Adapter;

use DateTime;
use Doctrine\ORM\QueryBuilder;
use Omeka\Api\Adapter\AbstractEntityAdapter;
use Omeka\Api\Request;
use Omeka\Entity\EntityInterface;
use Omeka\Stdlib\ErrorStore;
use RedactValues\Api\Representation\RedactValuesPatternRepresentation;
use RedactValues\Entity\RedactValuesPattern;

class RedactValuesPatternAdapter extends AbstractEntityAdapter
{
    public function getResourceName()
    {
        return 'redact_values_patterns';
    }

    public function getRepresentationClass()
    {
        return RedactValuesPatternRepresentation::class;
    }

    public function getEntityClass()
    {
        return RedactValuesPattern::class;
    }

    public function buildQuery(QueryBuilder $qb, array $query)
    {
    }

    public function validateRequest(Request $request, ErrorStore $errorStore)
    {
    }

    public function hydrate(Request $request, EntityInterface $entity, ErrorStore $errorStore)
    {
        if (Request::UPDATE === $request->getOperation()) {
            $entity->setModified(new DateTime('now'));
        }
        $this->hydrateOwner($request, $entity);
        if ($this->shouldHydrate($request, 'o:label')) {
            $entity->setLabel($request->getValue('o:label'));
        }
        if ($this->shouldHydrate($request, 'o-module-redact-values:pattern')) {
            $entity->setPattern($request->getValue('o-module-redact-values:pattern'));
        }
    }

    public function validateEntity(EntityInterface $entity, ErrorStore $errorStore)
    {
    }
}
