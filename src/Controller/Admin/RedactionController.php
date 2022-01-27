<?php
namespace RedactValues\Controller\Admin;

use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;
use Omeka\Form as OmekaForm;
use RedactValues\Form as RedactValuesForm;

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

    public function addAction()
    {
        $form = $this->getForm(RedactValuesForm\RedactionForm::class);

        if ($this->getRequest()->isPost()) {
            $postData = $this->params()->fromPost();
            $form->setData($postData);
            if ($form->isValid()) {
                $formData = $form->getData();
                $formData['o:property'] = ['o:id' => $formData['o:property']];
                $formData['o-module-redact-values:pattern'] = ['o:id' => $formData['o-module-redact-values:pattern']];
                $response = $this->api($form)->create('redact_values_redactions', $formData);
                if ($response) {
                    $this->messenger()->addSuccess('Redaction successfully added.'); // @translate
                    return $this->redirect()->toRoute('admin/redact-values-redaction', ['action' => 'browse'], true);
                }
            } else {
                $this->messenger()->addFormErrors($form);
            }
        }

        $view = new ViewModel;
        $view->setVariable('redaction', null);
        $view->setVariable('form', $form);
        return $view;
    }

    public function editAction()
    {
        $redaction = $this->api()->read('redact_values_redactions', $this->params('redaction-id'))->getContent();
        $form = $this->getForm(RedactValuesForm\RedactionForm::class);

        if ($this->getRequest()->isPost()) {
            $postData = $this->params()->fromPost();
            $form->setData($postData);
            if ($form->isValid()) {
                $formData = $form->getData();
                $formData['o:property'] = ['o:id' => $formData['o:property']];
                $formData['o-module-redact-values:pattern'] = ['o:id' => $formData['o-module-redact-values:pattern']];
                $response = $this->api($form)->update('redact_values_redactions', $redaction->id(), $formData);
                if ($response) {
                    $this->messenger()->addSuccess('Redaction successfully edited.');
                    return $this->redirect()->toRoute('admin/redact-values-redaction', ['action' => 'browse'], true);
                }
            } else {
                $this->messenger()->addFormErrors($form);
            }
        } else {
            $data = $redaction->getJsonLd();
            $data['o:property'] = $data['o:property'] ? $data['o:property']->id() : null;
            $data['o-module-redact-values:pattern'] = $data['o-module-redact-values:pattern'] ? $data['o-module-redact-values:pattern']->id() : null;
            $form->setData($data);
        }

        $view = new ViewModel;
        $view->setVariable('redaction', $redaction);
        $view->setVariable('form', $form);
        return $view;
    }

    public function deleteAction()
    {
        if ($this->getRequest()->isPost()) {
            $redaction = $this->api()->read('redact_values_redactions', $this->params('redaction-id'))->getContent();
            $form = $this->getForm(OmekaForm\ConfirmForm::class);
            $form->setData($this->getRequest()->getPost());
            if ($form->isValid()) {
                $response = $this->api($form)->delete('redact_values_redactions', $redaction->id());
                if ($response) {
                    $this->messenger()->addSuccess('Redaction successfully deleted.'); // @translate
                }
            } else {
                $this->messenger()->addFormErrors($form);
            }
        }
        return $this->redirect()->toRoute('admin/redact-values-redaction', ['action' => 'browse'], true);
    }
}
