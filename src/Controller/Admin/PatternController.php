<?php
namespace RedactValues\Controller\Admin;

use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;
use Omeka\Form as OmekaForm;
use RedactValues\Form as RedactValuesForm;

class PatternController extends AbstractActionController
{
    public function browseAction()
    {
        $this->setBrowseDefaults('created');
        $query = $this->params()->fromQuery();
        $response = $this->api()->search('redact_values_patterns', $query);
        $this->paginator($response->getTotalResults(), $this->params()->fromQuery('page'));
        $patterns = $response->getContent();

        $view = new ViewModel;
        $view->setVariable('patterns', $patterns);
        return $view;
    }

    public function addAction()
    {
        $form = $this->getForm(RedactValuesForm\PatternForm::class);

        if ($this->getRequest()->isPost()) {
            $postData = $this->params()->fromPost();
            $form->setData($postData);
            if ($form->isValid()) {
                $formData = $form->getData();
                $response = $this->api($form)->create('redact_values_patterns', $formData);
                if ($response) {
                    $this->messenger()->addSuccess('Pattern successfully added.'); // @translate
                    return $this->redirect()->toRoute('admin/redact-values-pattern', ['action' => 'browse'], true);
                }
            } else {
                $this->messenger()->addFormErrors($form);
            }
        }

        $view = new ViewModel;
        $view->setVariable('pattern', null);
        $view->setVariable('form', $form);
        return $view;
    }

    public function editAction()
    {
        $pattern = $this->api()->read('redact_values_patterns', $this->params('pattern-id'))->getContent();
        $form = $this->getForm(RedactValuesForm\PatternForm::class);

        if ($this->getRequest()->isPost()) {
            $postData = $this->params()->fromPost();
            $form->setData($postData);
            if ($form->isValid()) {
                $formData = $form->getData();
                $response = $this->api($form)->update('redact_values_patterns', $pattern->id(), $formData);
                if ($response) {
                    $this->messenger()->addSuccess('Pattern successfully edited.');
                    return $this->redirect()->toRoute('admin/redact-values-pattern', ['action' => 'browse'], true);
                }
            } else {
                $this->messenger()->addFormErrors($form);
            }
        } else {
            $data = $pattern->getJsonLd();
            $form->setData($data);
        }

        $view = new ViewModel;
        $view->setVariable('pattern', $pattern);
        $view->setVariable('form', $form);
        return $view;
    }

    public function deleteAction()
    {
        if ($this->getRequest()->isPost()) {
            $pattern = $this->api()->read('redact_values_patterns', $this->params('pattern-id'))->getContent();
            $form = $this->getForm(OmekaForm\ConfirmForm::class);
            $form->setData($this->getRequest()->getPost());
            if ($form->isValid()) {
                $response = $this->api($form)->delete('redact_values_patterns', $pattern->id());
                if ($response) {
                    $this->messenger()->addSuccess('Pattern successfully deleted.'); // @translate
                }
            } else {
                $this->messenger()->addFormErrors($form);
            }
        }
        return $this->redirect()->toRoute('admin/redact-values-pattern', ['action' => 'browse'], true);
    }
}
