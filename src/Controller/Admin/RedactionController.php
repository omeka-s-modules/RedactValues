<?php
namespace RedactValues\Controller\Admin;

use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;

class RedactionController extends AbstractActionController
{
    public function browseAction()
    {
        $this->setBrowseDefaults('created');
        $query = $this->params()->fromQuery();
        $response = $this->api()->search('redact_values_redactions', $query);
        $this->paginator($response->getTotalResults(), $this->params()->fromQuery('page'));
        $redactions = $response->getContent();

        $view = new ViewModel;
        $view->setVariable('redactions', $redactions);
        return $view;
    }
}
