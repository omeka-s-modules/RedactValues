<?php
namespace RedactValues\Api\Representation;

use Omeka\Api\Representation\AbstractEntityRepresentation;

class RedactValuesRedactionRepresentation extends AbstractEntityRepresentation
{
    public function getJsonLdType()
    {
        return 'o-module-osii:Redaction';
    }

    public function getJsonLd()
    {
        $owner = $this->owner();
        $modified = $this->modified();
        return [
            'o:owner' => $owner ? $owner->getReference() : null,
            'o:label' => $this->label(),
            'o-module-redact-values:resource_type' => $this->resourceType(),
            'o-module-redact-values:query' => $this->query(),
            'o-module-redact-values:pattern' => $this->pattern(),
            'o-module-redact-values:replacement' => $this->replacement(),
            'o-module-redact-values:allow_roles' => $this->allowRoles(),
            'o:property' => $this->property(),
            'o:created' => $this->getDateTime($this->created()),
            'o:modified' => $modified ? $this->getDateTime($modified) : null,
        ];
    }

    public function adminUrl($action = null, $canonical = false)
    {
        $url = $this->getViewHelper('Url');
        return $url(
            'admin/redact-values-redaction-id',
            [
                'controller' => 'redaction',
                'action' => $action,
                'redaction-id' => $this->id(),
            ],
            ['force_canonical' => $canonical]
        );
    }

    public function owner()
    {
        return $this->getAdapter('users')->getRepresentation($this->resource->getOwner());
    }

    public function label()
    {
        return $this->resource->getLabel();
    }

    public function resourceType()
    {
        return $this->resource->getResourceType();
    }

    public function query()
    {
        return $this->resource->getQuery();
    }

    public function property()
    {
        return $this->getAdapter('properties')->getRepresentation($this->resource->getProperty());
    }

    public function pattern()
    {
        return $this->resource->getPattern();
    }

    public function replacement()
    {
        return $this->resource->getReplacement();
    }

    public function allowRoles()
    {
        return $this->resource->getAllowRoles();
    }

    public function created()
    {
        return $this->resource->getCreated();
    }

    public function modified()
    {
        return $this->resource->getModified();
    }

    public function resourceTypeLabel()
    {
        $resourceTypes = [
            'items' => 'Items', // @translate
            'item_sets' => 'Item sets', // @translate
            'media' => 'Media', // @translate
        ];
        return $resourceTypes[$this->resourceType()];
    }
}
