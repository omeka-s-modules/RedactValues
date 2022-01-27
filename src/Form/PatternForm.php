<?php
namespace RedactValues\Form;

use Laminas\Form\Form;
use Laminas\Form\Element as LaminasElement;

class PatternForm extends Form
{
    public function init()
    {
        $this->add([
            'type' => LaminasElement\Text::class,
            'name' => 'o:label',
            'options' => [
                'label' => 'Label', // @translate
                'info' => 'Enter the label of this pattern.', // @translate
            ],
            'attributes' => [
                'id' => 'redact-values-label',
                'required' => true,
            ],
        ]);
        $this->add([
            'type' => LaminasElement\Textarea::class,
            'name' => 'o-module-redact-values:pattern',
            'options' => [
                'label' => 'Pattern', // @translate
                'info' => 'Enter the regular expression pattern that identifies the sequence of characters that will be redacted. For information on regular expressions, see <a href="https://www.regular-expressions.info/" target="_blank">https://www.regular-expressions.info/</a>', // @translate
                'escape_info' => false,
            ],
            'attributes' => [
                'id' => 'redact-values-pattern',
            ],
        ]);
    }
}
