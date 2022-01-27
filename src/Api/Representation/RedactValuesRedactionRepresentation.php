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

    public function created()
    {
        return $this->resource->getCreated();
    }

    public function modified()
    {
        return $this->resource->getModified();
    }
}
