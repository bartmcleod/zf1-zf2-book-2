<?php
/**
 * ZF 1 -> ZF 2 migration
 *
 * Example of a simple custom view helper.
 *
 * @author Bart McLeod (mcleod@spaceweb.nl)
 */
namespace Book\View\Helper;
use Zend\View\Helper\AbstractHelper;

class HelloSimple extends AbstractHelper {

    public function __invoke(){
        return 'Simple hello from ViewHelper HelloSimple!';
    }

}
