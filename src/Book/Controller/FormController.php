<?php
/**
 * ZF 1 -> ZF 2 migration
 *
 * Controller used to demonstrate form migration.
 *
 * @author Bart McLeod (mcleod@spaceweb.nl)
 */
namespace Book\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\Form\Form;
use Zend\Form\Element;
use Zend\View\Model\ViewModel as View;
use Zend\InputFilter\InputFilter;

class FormController extends AbstractActionController
{
    /**
     * Displays a very simple form with two fields, firstname and
     * lastname and a submit button.
     *
     * @return array|\Zend\View\Model\ViewModel
     */
    public function indexAction()
    {
        $form = new Form('person');
        $firstname = new Element\Text('firstname');
        $firstname
            ->setLabel('First name')
            ->setAttribute('id', 'firstname');

        $lastname = new Element\Text('lastname');
        $lastname
            ->setLabel('Last name')
            ->setAttribute('id', 'lastname');

        $submit = new Element\Submit('save');
        $submit
            ->setAttribute('id', 'save')
            ->setValue('Send');
        $form
            ->add($firstname)
            ->add($lastname)
            ->add($submit);

        $view = new View();
        $view->form = $form;
        return $view;
    }

    /**
     * Filters and validates a dataset containing a 'firstname' field
     * @return array Associative array with view data
     */
    public function filterAction()
    {
        $filter = new InputFilter();
        $filter->add(
            array(
                'name'       => 'firstname',
                'filters'    => array(
                    array(
                        'name' => 'string_trim',
                    ),
                ),
                'validators' => array(
                    array(
                        'name' => 'regex',
                        'options' => array(
                            'pattern' => "/^[a-z]*$/i",
                        )
                    ),
                ),
            )
        );
        $filter->add(array('name' => 'lastname'));

        $data = array(
            'firstname' => ' James (007) ',
            'lastname'  => 'Bond',
        );

        $filter->setData($data);

        $valid = $filter->isValid();

        // use the filtered values as view data
        $view = $filter->getValues();

        // add some information
        $view['valid'] = $valid;
        $view['messages'] = $filter->getMessages();

        // return the view data
        return $view;
    }
}
