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
            'type' => OmekaElement\PropertySelect::class,
            'name' => 'redactions[__INDEX__][property_id]',
            'options' => [
                'label' => 'Property', // @translate
                'empty_option' => 'Select a property…', // @translate
            ],
            'attributes' => [
                'class' => 'property-id',
            ],
        ]);
        $this->add([
            'type' => LaminasElement\Textarea::class,
            'name' => 'redactions[__INDEX__][pattern]',
            'options' => [
                'label' => 'Pattern', // @translate
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
            ],
            'attributes' => [
                'class' => 'replacement',
            ],
        ]);
        // Note that global_admin, site_admin, and editor roles can update all
        // resources, and therefore can view redacted values, so there's no
        // reason to add them here.
        $this->add([
            'type' => LaminasElement\MultiCheckbox::class,
            'name' => 'redactions[__INDEX__][allow]',
            'options' => [
                'label' => 'Allow', // @translate
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
