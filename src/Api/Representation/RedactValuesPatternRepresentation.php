<?php
namespace RedactValues\Api\Representation;

use Omeka\Api\Representation\AbstractEntityRepresentation;

class RedactValuesPatternRepresentation extends AbstractEntityRepresentation
{
    public function getJsonLdType()
    {
        return 'o-module-osii:Pattern';
    }

    public function getJsonLd()
    {
        $owner = $this->owner();
        $modified = $this->modified();
        return [
            'o:owner' => $owner ? $owner->getReference() : null,
            'o:label' => $this->label(),
            'o-module-redact-values:pattern' => $this->pattern(),
            'o:created' => $this->getDateTime($this->created()),
            'o:modified' => $modified ? $this->getDateTime($modified) : null,
        ];
    }

    public function adminUrl($action = null, $canonical = false)
    {
        $url = $this->getViewHelper('Url');
        return $url(
            'admin/redact-values-pattern-id',
            [
                'controller' => 'pattern',
                'action' => $action,
                'pattern-id' => $this->id(),
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

    public function pattern()
    {
        return $this->resource->getPattern();
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
