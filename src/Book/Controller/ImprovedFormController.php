<?php
/**
 * ZF 1 -> ZF 2 migration
 *
 * A controller that uses a form to save a PersonEntity to the database.
 *
 * @author Bart McLeod (mcleod@spaceweb.nl)
 */
namespace Book\Controller;

use Book\Entity\PersonEntity;
use Zend\Db\TableGateway\TableGateway;
use Zend\Mvc\Controller\AbstractActionController;
use Book\Form\PersonForm as Form;
use Zend\Form\Element;
use Zend\Stdlib\Hydrator\ObjectProperty;
use Zend\View\Model\ViewModel;

class ImprovedFormController extends AbstractActionController
{
    /**
     * Displays the custom person form, and makes it use the process action.
     *
     * @return array
     */
    public function indexAction()
    {
        $form = new Form('person');
        $form->setAttribute('action', $this->url()->fromRoute('form2', array('action' => 'process')));
        return array('form' => $form);
    }

    /**
     * Validates the form, hydrates the person object and dummps it to the view.
     *
     * @return array
     */
    public function processAction()
    {
        $form = new Form('person');
        $post = $this->params()->fromPost();
        $person = new PersonEntity();
        $form->setObject($person);
        $form->setHydrator(new ObjectProperty());
        $form->setData($post);

        return array(
            'valid' => $form->isValid() ? 'valid' : 'INVALID',
            'person' => $person,
        );
    }

    /**
     * Displays the custom person form, and makes it use the save action.
     *
     * It also asigns a the custom 'form' template to the view model, so there is no new.phtml template.
     *
     * @return \Zend\View\Model\ViewModel
     */
    public function newAction()
    {
        $form = new Form('person');
        $form->setAttribute('action', $this->url()->fromRoute('form2', array('action' => 'save')));
        $view = new ViewModel(array('form' => $form));
        $view->setTemplate('form');
        return $view;
    }

    /**
     * Save the hydrated person object using a concrete table gateway.
     *
     * @return array
     */
    public function saveAction()
    {
        $form = new Form('person');
        $post = $this->params()->fromPost();
        $person = new PersonEntity();
        $form->setObject($person);
        $form->setHydrator(new ObjectProperty());
        $form->setData($post);

        if ($form->isValid()) {
            $adapter = $this->getServiceLocator()->get('Book\Db');
            $personTable = new TableGateway('person', $adapter);
            $personData = $person->getInputFilter()->getValues();
            $personTable->insert($personData);
            $result = $personTable->getLastInsertValue();
        } else {
            $result = $form->getMessages();
        }

        return compact('result');
    }
}
