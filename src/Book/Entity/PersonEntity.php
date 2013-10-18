<?php
/**
 * ZF 1 -> ZF 2 migration
 *
 * Basic object with a very simple representation of a person
 *
 * @author Bart McLeod (mcleod@spaceweb.nl)
 */
namespace Book\Entity;


use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;
use Book\Filter\PersonFilter;

class PersonEntity implements InputFilterAwareInterface {
    protected $inputFilter;
    public $id;
    public $firstname;
    public $lastname;

    /**
     * Set input filter
     *
     * @param  InputFilterInterface $inputFilter
     * @return InputFilterAwareInterface
     */
    public function setInputFilter(InputFilterInterface $inputFilter)
    {
        $this->inputFilter = $inputFilter;
    }

    /**
     * Retrieve input filter
     *
     * @return InputFilterInterface
     */
    public function getInputFilter()
    {
        if (is_null($this->inputFilter)) {
            $this->inputFilter = new PersonFilter();
        }

        return $this->inputFilter;
    }
}
