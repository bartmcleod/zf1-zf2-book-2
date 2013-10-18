<?php
/**
 * ZF 1 -> ZF 2 migration
 *
 * Example of a custom input filter
 *
 * @author Bart McLeod (mcleod@spaceweb.nl)
 */

namespace Book\Filter;

use Zend\InputFilter\InputFilter;


class PersonFilter extends InputFilter{
    public function __construct()
    {
        $this->add(
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
        $this->add(array('name' => 'id'), array('name' => 'lastname'));
    }
}
