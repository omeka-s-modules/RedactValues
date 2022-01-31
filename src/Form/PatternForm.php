<?php
namespace RedactValues\Form;

use Laminas\Form\Form;
use Laminas\Form\Element as LaminasElement;
use Laminas\Validator\Callback;

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
                'info' => 'Enter the regular expression pattern that identifies the sequence of characters that will be redacted. You must enclose the pattern with <a href="https://www.php.net/manual/en/regexp.reference.delimiters.php" target="_blank">delimiters</a>. You may use <a href="https://www.php.net/manual/en/reference.pcre.pattern.modifiers.php" target="_blank">modifiers</a>.<br><br>For more information on regular expressions, see <a href="https://www.regular-expressions.info/" target="_blank">Regular-Expressions.info</a> and <a href="https://www.php.net/manual/en/pcre.pattern.php" target="_blank">PCRE Patterns</a>. To validate your pattern, try <a href="https://regexr.com/" target="_blank">RegExr</a>.', // @translate
                'escape_info' => false,
            ],
            'attributes' => [
                'id' => 'redact-values-pattern',
                'required' => true,
            ],
        ]);

        $inputFilter = $this->getInputFilter();
        // Validate the pattern.
        $validator = (new Callback)
            ->setMessage('Invalid pattern. Are you sure you\'ve included delimiters?', Callback::INVALID_VALUE) // @translate
            ->setCallback(function ($pattern) {
                // preg_match() will return false if the pattern is invalid.
                // Note the use of @ to suppress error messages.
                return !(false === @preg_match($pattern, null));
            });
        $inputFilter->add([
            'name' => 'o-module-redact-values:pattern',
            'required' => true,
            'validators' => [$validator],
        ]);
    }
}
