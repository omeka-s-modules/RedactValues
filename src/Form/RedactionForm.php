<?php
namespace RedactValues\Form;

use Laminas\Form\Form;
use Laminas\Form\Element as LaminasElement;
use Omeka\Form\Element as OmekaElement;

class RedactionForm extends Form
{
    public function init()
    {
        $this->add([
            'type' => LaminasElement\Text::class,
            'name' => 'o:label',
            'options' => [
                'label' => 'Label', // @translate
                'info' => 'Enter the label of this redaction.', // @translate
            ],
            'attributes' => [
                'id' => 'redact-values-label',
                'required' => true,
            ],
        ]);
        $this->add([
            'type' => LaminasElement\Select::class,
            'name' => 'o-module-redact-values:resource_type',
            'options' => [
                'label' => 'Resource type', // @translate
                'info' => 'Select the resource type from which to redact text.', // @translate
                'value_options' => [
                    'items' => 'Items', // @translate
                    'item_sets' => 'Item sets', // @translate
                    'media' => 'Media', // @translate
                ],
            ],
            'attributes' => [
                'id' => 'redact-values-resource-type',
                'required' => true,
            ],
        ]);
        $this->add([
            'type' => OmekaElement\Query::class,
            'name' => 'o-module-redact-values:query',
            'options' => [
                'label' => 'Query',
                'info' => 'Enter a query used to filter the resources from which to redact text. No query means all resources of the specified resource type.', // @translate
            ],
            'attributes' => [
                'id' => 'query',
            ],
        ]);
        $this->add([
            'type' => OmekaElement\PropertySelect::class,
            'name' => 'o:property',
            'options' => [
                'label' => 'Property', // @translate
                'info' => 'Select the property from which to redact text.', // @translate
                'empty_option' => '',
            ],
            'attributes' => [
                'id' => 'redact-values-property-id',
                'class' => 'chosen-select',
                'required' => true,
                'data-placeholder' => 'Select a property…', // @translate
            ],
        ]);
        $this->add([
            'type' => LaminasElement\Textarea::class,
            'name' => 'o-module-redact-values:pattern',
            'options' => [
                'label' => 'Pattern', // @translate
                'info' => 'Enter the regular expression pattern that identifies the text that will be redacted. For information on regular expressions, see <a href="https://www.regular-expressions.info/" target="_blank">https://www.regular-expressions.info/</a>', // @translate
                'escape_info' => false,
            ],
            'attributes' => [
                'id' => 'redact-values-pattern',
            ],
        ]);
        $this->add([
            'type' => LaminasElement\Text::class,
            'name' => 'o-module-redact-values:replacement',
            'options' => [
                'label' => 'Replacement', // @translate
                'info' => 'Enter the text that will be used to replace the redacted text.', // @translate
            ],
            'attributes' => [
                'id' => 'redact-values-replacement',
            ],
        ]);
        // Note that global_admin, site_admin, and editor roles can update all
        // resources, and therefore can view redacted text, so there's no reason
        // to add them here.
        $this->add([
            'type' => LaminasElement\MultiCheckbox::class,
            'name' => 'o-module-redact-values:allow_roles',
            'options' => [
                'label' => 'Allow roles', // @translate
                'info' => 'Allow users with the following roles to view redacted text. Note that any user with permission to update a resource can automatically view its redacted text.', // @translate
                'value_options' => [
                    'author' => 'Author', // @translate
                    'researcher' => 'Researcher', // @translate
                ],
            ],
            'attributes' => [
                'id' => 'redact-values-allow-roles',
            ],
        ]);

        $inputFilter = $this->getInputFilter();
        $inputFilter->add([
            'name' => 'o-module-redact-values:allow_roles',
            'allow_empty' => true,
        ]);
    }
}
