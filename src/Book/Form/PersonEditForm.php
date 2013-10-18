<?php
/**
 * ZF 1 -> ZF 2 migration
 *
 * Example of a custom form with a hidden 'id' element,
 * so that it can be used for editing.
 *
 * @author Bart McLeod (mcleod@spaceweb.nl)
 */

namespace Book\Form;

use Zend\Form\Form;
use Zend\Form\Element;


class PersonEditForm extends PersonForm {

    protected function setupElements()
    {
        $id = new Element\Hidden('id');
        $this->add($id);

        parent::setupElements();

        $validationGroup = $this->getValidationGroup();
        $validationGroup[] = 'id';
        $this->setValidationGroup($validationGroup);
    }
}
