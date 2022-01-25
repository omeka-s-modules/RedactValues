<?php
namespace RedactValues;

use Omeka\Api\Representation\AbstractResourceEntityRepresentation;
use Omeka\Module\AbstractModule;
use Laminas\EventManager\Event;
use Laminas\EventManager\SharedEventManagerInterface;
use Laminas\Form\Element;
use Laminas\Mvc\Controller\AbstractController;
use Laminas\Mvc\MvcEvent;
use Laminas\ServiceManager\ServiceLocatorInterface;
use Laminas\View\Renderer\PhpRenderer;
use RedactValues\Form\RedactionFormTemplate;

class Module extends AbstractModule
{
    protected $redactions;

    public function getConfig()
    {
        return include sprintf('%s/config/module.config.php', __DIR__);
    }

    public function getConfigForm(PhpRenderer $renderer)
    {
        $formTemplate = $this->getServiceLocator()
            ->get('FormElementManager')
            ->get(RedactionFormTemplate::class);
        return $renderer->partial('common/redact_values_config', [
            'redactions' => $this->getRedactions(),
            'formTemplate' => $formTemplate,
        ]);
    }

    public function handleConfigForm(AbstractController $controller)
    {
        $redactions = [];
        foreach ($controller->params()->fromPost('redactions') as $redaction) {
            if (empty($redaction['property_id'])) {
                continue; // Property must be set
            }
            $redactions[$redaction['property_id']][] = [$redaction['pattern'], $redaction['replacement']];
        }
        $this->getServiceLocator()
            ->get('Omeka\Settings')
            ->set('redact_values_redactions', $redactions);
    }

    public function attachListeners(SharedEventManagerInterface $sharedEventManager)
    {
        $sharedEventManager->attach(
            '*',
            'rep.value.html',
            function (Event $event) {
                $value = $event->getTarget();
                $property = $value->property();
                $html = $event->getParam('html');
                $html = $this->redact($property->id(), $html);
                $event->setParam('html', $html);
            }
        );
        $sharedEventManager->attach(
            '*',
            'rep.value.json',
            function (Event $event) {
                $value = $event->getTarget();
                $json = $event->getParam('json');
                if (isset($json['@value'])) {
                    $json['@value'] = $this->redact($json['property_id'], $json['@value']);
                }
                $event->setParam('json', $json);
            }
        );
        $sharedEventManager->attach(
            '*',
            'rep.value.string',
            function (Event $event) {
                $value = $event->getTarget();
                $property = $value->property();
                $string = $event->getParam('string');
                $string = $this->redact($property->id(), $string);
                $event->setParam('string', $string);
            }
        );
    }

    public function redact($propertyId, $subject)
    {
        $redactions = $this->getRedactions();
        if (!isset($redactions[$propertyId])) {
            return $subject;
        }
        foreach ($redactions[$propertyId] as $redaction) {
            $pattern = $redaction[0];
            $replacement = $redaction[1];
            $subject = preg_replace(sprintf('/%s/', $pattern), $replacement, $subject);
        }
        return $subject;
    }

    public function getRedactions()
    {
        if (isset($this->redactions)) {
            return $this->redactions;
        }
        $settings = $this->getServiceLocator()->get('Omeka\Settings');
        $this->redactions = $settings->get('redact_values_redactions', []);
        return $this->redactions;
    }
}
