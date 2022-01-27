<?php
namespace RedactValues\Api\Adapter;

use DateTime;
use Doctrine\ORM\QueryBuilder;
use Omeka\Api\Adapter\AbstractEntityAdapter;
use Omeka\Api\Request;
use Omeka\Entity\EntityInterface;
use Omeka\Stdlib\ErrorStore;
use RedactValues\Api\Representation\RedactValuesRedactionRepresentation;
use RedactValues\Entity\RedactValuesRedaction;

class RedactValuesRedactionAdapter extends AbstractEntityAdapter
{
    public function getResourceName()
    {
        return 'redact_values_redactions';
    }

    public function getRepresentationClass()
    {
        return RedactValuesRedactionRepresentation::class;
    }

    public function getEntityClass()
    {
        return RedactValuesRedaction::class;
    }

    public function buildQuery(QueryBuilder $qb, array $query)
    {
    }

    public function validateRequest(Request $request, ErrorStore $errorStore)
    {
        if (isset($data['o:property']) && !isset($data['o:property']['o:id'])) {
            $errorStore->addError('o:property', 'Invalid property format passed in request.'); // @translate
        }
        if (isset($data['o-module-redact-values:pattern']) && !isset($data['o-module-redact-values:pattern']['o:id'])) {
            $errorStore->addError('o-module-redact-values:pattern', 'Invalid pattern format passed in request.'); // @translate
        }
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
        if ($this->shouldHydrate($request, 'o-module-redact-values:resource_type')) {
            $entity->setResourceType($request->getValue('o-module-redact-values:resource_type'));
        }
        if ($this->shouldHydrate($request, 'o-module-redact-values:query')) {
            $entity->setQuery($request->getValue('o-module-redact-values:query'));
        }
        if ($this->shouldHydrate($request, 'o:property')) {
            $property = $request->getValue('o:property');
            $entity->setProperty($this->getAdapter('properties')->findEntity($property['o:id']));
        }
        if ($this->shouldHydrate($request, 'o-module-redact-values:pattern')) {
            $pattern = $request->getValue('o-module-redact-values:pattern');
            $entity->setPattern($this->getAdapter('redact_values_patterns')->findEntity($pattern['o:id']));
        }
        if ($this->shouldHydrate($request, 'o-module-redact-values:replacement')) {
            $entity->setReplacement($request->getValue('o-module-redact-values:replacement'));
        }
        if ($this->shouldHydrate($request, 'o-module-redact-values:allow_roles')) {
            $entity->setAllowRoles($request->getValue('o-module-redact-values:allow_roles') ?? []);
        }
    }

    public function validateEntity(EntityInterface $entity, ErrorStore $errorStore)
    {
    }
}
