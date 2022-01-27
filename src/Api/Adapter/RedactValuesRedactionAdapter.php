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
    }

    public function validateEntity(EntityInterface $entity, ErrorStore $errorStore)
    {
        if (!is_string($entity->getLabel()) || '' === $entity->getLabel()) {
            $errorStore->addError('o:label', 'An import must have a label'); // @translate
        }
    }
}
