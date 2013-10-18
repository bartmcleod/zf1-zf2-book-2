<?php
/**
 * ZF 1 -> ZF 2 migration
 *
 * Example of a custom form
 *
 * @author Bart McLeod (mcleod@spaceweb.nl)
 */

namespace Book\Form;

use Zend\Form\Form;
use Zend\Form\Element;


class PersonForm extends Form {
    public function __construct($name = null, $options = array())
    {
        parent::__construct($name, $options);
        $this->setupElements();
    }

    protected function setupElements()
    {
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
        $this
            ->add($firstname)
            ->add($lastname)
            ->add($submit);


        $this->setValidationGroup(
            array(
                'firstname',
                'lastname',
            )
        );
    }
}