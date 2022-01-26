<?php
namespace RedactValues;

use Omeka\Api\Representation\ValueRepresentation;
use Omeka\Module\AbstractModule;
use Laminas\EventManager\Event;
use Laminas\EventManager\SharedEventManagerInterface;
use Laminas\Mvc\Controller\AbstractController;
use Laminas\View\Renderer\PhpRenderer;
use RedactValues\Form\RedactionFormTemplate;

class Module extends AbstractModule
{
    protected $redactions;
    protected $role;
    protected $resourceIds = [];

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
            $redactions[$redaction['property_id']][] = [
                'resource_type' => $redaction['resource_type'],
                'query' => $redaction['query'],
                'pattern' => $redaction['pattern'],
                'replacement' => $redaction['replacement'],
                'allow' => $redaction['allow'] ?? [],
            ];
        }
        $this->getServiceLocator()
            ->get('Omeka\Settings')
            ->set('redact_values_redactions', $redactions);
    }

    public function attachListeners(SharedEventManagerInterface $sharedEventManager)
    {
        // Redact value HTML.
        $sharedEventManager->attach(
            '*',
            'rep.value.html',
            function (Event $event) {
                $value = $event->getTarget();
                $html = $event->getParam('html');
                $html = $this->redact($value, $html);
                $event->setParam('html', $html);
            }
        );
        // Redact value JSON.
        $sharedEventManager->attach(
            '*',
            'rep.value.json',
            function (Event $event) {
                $value = $event->getTarget();
                $json = $event->getParam('json');
                if (isset($json['@value'])) {
                    $json['@value'] = $this->redact($value, $json['@value']);
                }
                $event->setParam('json', $json);
            }
        );
        // Redact value string.
        $sharedEventManager->attach(
            '*',
            'rep.value.string',
            function (Event $event) {
                $value = $event->getTarget();
                $string = $event->getParam('string');
                $string = $this->redact($property->id(), $string);
                $event->setParam('string', $string);
            }
        );
    }

    /**
     * Redact text.
     *
     * @param ValueRepresentation $value
     * @param string $text The text to redact
     * @return string The redacted text
     */
    public function redact(ValueRepresentation $value, $text)
    {
        $resource = $value->resource();
        if ($resource->userIsAllowed('update')) {
            // Don't redact if user is allowed to update the resource.
            return $text;
        }
        $redactions = $this->getRedactions();
        $propertyId = $value->property()->id();
        if (!isset($redactions[$propertyId])) {
            // No redction exists for this property.
            return $text;
        }
        foreach ($redactions[$propertyId] as $redaction) {
            if (in_array($this->getRole(), $redaction['allow'])) {
                // Don't redact if the current user's role is allowed.
                continue;
            }
            // An empty query is all resources, so there's no need to get the IDs.
            if ($redaction['query']) {
                $resourceIds = $this->getResourceIds($redaction['resource_type'], $redaction['query']);
                if (!in_array($resource->id(), $resourceIds)) {
                    // Don't redact if the resource is not in the query result.
                    continue;
                }
            }
            $text = preg_replace(
                sprintf('/%s/', $redaction['pattern']),
                $redaction['replacement'],
                $text
            );
        }
        return $text;
    }

    /**
     * Get configured redactions.
     *
     * @return array
     */
    public function getRedactions()
    {
        if (isset($this->redactions)) {
            return $this->redactions;
        }
        $settings = $this->getServiceLocator()->get('Omeka\Settings');
        $this->redactions = $settings->get('redact_values_redactions', []);
        return $this->redactions;
    }

    /**
     * Get the current user's role.
     *
     * @return string An empty string is no role (non logged in user)
     */
    public function getRole()
    {
        if (isset($this->role)) {
            return $this->role;
        }
        $user = $this->getServiceLocator()->get('Omeka\AuthenticationService')->getIdentity();
        $this->role = $user ? $user->getRole() : '';
        return $this->role;
    }

    /**
     * Get the resource IDs for this resource type and query.
     *
     * @param string $resourceType items, item_sets, or media
     * @param string $query The query string
     * @return array An array of resource IDs
     */
    public function getResourceIds($resourceType, $query)
    {
        if (isset($this->resourceIds[$query])) {
            return $this->resourceIds[$query];
        }
        parse_str($query, $queryArray);
        $api = $this->getServiceLocator()->get('Omeka\ApiManager');
        $this->resourceIds[$query] = $api->search($resourceType, $queryArray, ['returnScalar' => 'id'])->getContent();
        return $this->resourceIds[$query];
    }
}
