<?php
/**
 * ZF 1 -> ZF 2 migration
 *
 * This controller elaborates on what the ImprovedFormController did
 * and demonstrates some more advanced features of Zend\Db.
 *
 * @author Bart McLeod (mcleod@spaceweb.nl)
 */
namespace Book\Controller;

use Book\Entity\PersonEntity;
use Book\Form\PersonEditForm;
use Book\Mapper\PersonEasySaveMapper;
use Book\Table\PersonHydratorTable;
use Book\Table\PersonRowTable;
use Book\Table\PersonTable;
use Zend\Db\TableGateway\Feature\GlobalAdapterFeature;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Form\Element;
use Zend\Stdlib\Hydrator\ObjectProperty;
use Zend\View\Model\ViewModel;


class PersonController extends AbstractActionController
{
    /**
     * Displays the custom person form
     *
     * Go to /person to see it in action.
     *
     * @return array|\Zend\View\Model\ViewModel
     */
    public function indexAction()
    {
        $form = new PersonEditForm('person');
        $form->setAttribute('action', $this->url()->fromRoute('person', array('action' => 'save')));
        $view = new ViewModel();
        $view->form = $form;
        $view->setTemplate('form');
        return $view;
    }

    /**
     * Saves the person when the form is posted to this action.
     *
     * It uses a custom TableGateway object (Book\Table\PersonTable)
     *
     * @return array
     */
    public function saveAction()
    {
        $form = new PersonEditForm('person');
        $post = $this->params()->fromPost();
        $person = new PersonEntity();
        $form->setObject($person);
        $form->setHydrator(new ObjectProperty());
        $form->setData($post);

        if ($form->isValid()) {
            $adapter = $this->getServiceLocator()->get('Book\Db');
            $personTable = new PersonTable($adapter);
            $personData = $person->getInputFilter()->getValues();

            if (empty($personData['id'])) {
                $personTable->insert($personData);
                $result = $personTable->getLastInsertValue();
            } else {
                $personTable->update($personData, array('id' => $personData['id']));
                $result = $personData['id'];
            }
        } else {
            $result = $form->getMessages();
        }

        return compact('result');
    }

    /**
     * This is a rather simple function that displays the details of a person
     * with id==1.
     *
     * It demonstrates that the Book\Table\PersonRowTable return a RowGateway object.
     *
     * To see this in action go to /person/details
     *
     * @return array
     */
    public function detailsAction()
    {
        $adapter = $this->getServiceLocator()->get('Book\Db');
        $personTable = new PersonRowTable($adapter);
        $result = $personTable->select(array('id' => 1));
        return array('person' => $result->current());
    }

    /**
     * This is a regular edit action, that displays a person form with the value of
     * the given person filled in. An id parameter is provided in the route to
     * select the person.
     *
     * Go to /person/edit/{id} to see this in action
     *
     * @return ViewModel
     */
    public function editAction()
    {
        $form = new PersonEditForm('person');
        $form->setAttribute('action', $this->url()->fromRoute('person', array('action' => 'save')));
        $view = new ViewModel();

        $view->setTemplate('editform');

        $id = (int) $this->getEvent()->getRouteMatch()->getParam('id');
        $adapter = $this->getServiceLocator()->get('Book\Db');
        $personTable = new PersonRowTable($adapter);
        $result = $personTable->select(array('id' => $id));
        $person = $result->current();

        $form->get('id')->setValue($person->id);
        $form->get('firstname')->setValue($person->firstname);
        $form->get('lastname')->setValue($person->lastname);
        $view->form = $form;
        return $view;
    }

    /**
     * This is an improved edit action, that displays a person form with the value of
     * the given person filled in. An id parameter is provided in the route to
     * select the person.
     *
     * It uses a hydrator to bind the person object to the form.
     *
     * It uses the easy-save method to save the person object.
     *
     * Go to /person/hydrate/{id} to see this in action
     *
     * @return ViewModel
     */
    public function hydrateAction()
    {
        $form = new PersonEditForm('person');
        $form->setAttribute('action', $this->url()->fromRoute('person', array('action' => 'easy-save')));
        $view = new ViewModel();

        $view->setTemplate('editform');

        $id = (int) $this->getEvent()->getRouteMatch()->getParam('id');
        $adapter = $this->getServiceLocator()->get('Book\Db');
        $personTable = new PersonHydratorTable($adapter);
        $result = $personTable->select(array('id' => $id));
        $person = $result->current();
        // use a hydrator to extract a person entity
        $form->setHydrator(new ObjectProperty());
        $form->bind($person);
        $view->form = $form;
        $view->setTemplate('editform');
        return $view;
    }

    /**
     * This is an experimental save action, that is not (fully) described in the book.
     *
     * It uses a mapper, which is basically just a custom RowGateway implementation
     * with the added feature that is automatically detects if the person exists in the database.
     * This can only be done when there is an auto key on the table.
     *
     * When the mapper is hydrated, it knows if the row exists, so when we call 'save',
     * it will decide by itself whether to insert a new row or to update an existing row.
     *
     * @return array
     */
    public function easySaveAction()
    {
        $adapter = $this->getServiceLocator()->get('Book\Db');
        GlobalAdapterFeature::setStaticAdapter($adapter);
        $form = new PersonEditForm('person');
        $post = $this->params()->fromPost();
        $personMapper = new PersonEasySaveMapper();
        $form->setObject($personMapper);
        $form->setHydrator(new ObjectProperty());
        $form->setData($post);

        if ($form->isValid()) {
            $result = $personMapper->save();
        } else {
            $result = $form->getMessages();
        }

        return compact('result');
    }
}
