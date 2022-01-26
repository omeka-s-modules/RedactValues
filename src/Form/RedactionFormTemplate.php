<?php
namespace RedactValues\Form;

use Laminas\Form\Form;
use Laminas\Form\Element as LaminasElement;
use Omeka\Form\Element as OmekaElement;

class RedactionFormTemplate extends Form
{
    public function init()
    {
        $this->remove('redactionformtemplate_csrf');
        $this->add([
            'type' => LaminasElement\Select::class,
            'name' => 'redactions[__INDEX__][resource_type]',
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
                'class' => 'resource-type',
                'required' => true,
            ],
        ]);
        $this->add([
            'type' => OmekaElement\Query::class,
            'name' => 'redactions[__INDEX__][query]',
            'options' => [
                'label' => 'Query',
                'info' => 'Enter a query used to filter the resources from which to redact text.', // @translate
            ],
            'attributes' => [
                // Deliberately not including a class because doing so would
                // break the query element.
            ],
        ]);
        $this->add([
            'type' => OmekaElement\PropertySelect::class,
            'name' => 'redactions[__INDEX__][property_id]',
            'options' => [
                'label' => 'Property', // @translate
                'info' => 'Select the property from which to redact text.', // @translate
                'empty_option' => '',
            ],
            'attributes' => [
                'class' => 'property-id',
                'required' => true,
                'data-placeholder' => 'Select a property…', // @translate
            ],
        ]);
        $this->add([
            'type' => LaminasElement\Textarea::class,
            'name' => 'redactions[__INDEX__][pattern]',
            'options' => [
                'label' => 'Pattern', // @translate
                'info' => 'Enter the regular expression pattern that identifies the text that will be redacted. For information on regular expressions, see <a href="https://www.regular-expressions.info/" target="_blank">https://www.regular-expressions.info/</a>', // @translate
                'escape_info' => false,
            ],
            'attributes' => [
                'class' => 'pattern',
            ],
        ]);
        $this->add([
            'type' => LaminasElement\Text::class,
            'name' => 'redactions[__INDEX__][replacement]',
            'options' => [
                'label' => 'Replacement', // @translate
                'info' => 'Enter the text that will be used to replace the redacted text.', // @translate
            ],
            'attributes' => [
                'class' => 'replacement',
            ],
        ]);
        // Note that global_admin, site_admin, and editor roles can update all
        // resources, and therefore can view redacted text, so there's no reason
        // to add them here.
        $this->add([
            'type' => LaminasElement\MultiCheckbox::class,
            'name' => 'redactions[__INDEX__][allow]',
            'options' => [
                'label' => 'Allow', // @translate
                'info' => 'Allow users with the following roles to view redacted text. Note that any user with permission to update a resource can view its redacted text.', // @translate
                'value_options' => [
                    'author' => 'Author', // @translate
                    'researcher' => 'Researcher', // @translate
                ],
            ],
            'attributes' => [
                'class' => 'allow',
            ],
        ]);
    }
}
