<?php
/**
 * ZF 1 -> ZF 2 migration
 *
 * Rather academic custom ExceptionStrategy,
 * mostly here to demonstrate the flexibility of ZF 2.
 *
 * @author Bart McLeod (mcleod@spaceweb.nl)
 */
namespace Book;

use Zend\Mvc\View\Http\ExceptionStrategy
    as ZendExceptionStrategy;
use Zend\Mvc\MvcEvent;

class ExceptionStrategy
    extends ZendExceptionStrategy {

    public function prepareExceptionViewModel(MvcEvent $e)
    {
        if ($this->displayExceptions() && $exception = $e->getError()) {
            $exception = $e->getParam('exception');

            if ($exception instanceof \Exception) {
                throw($exception);
            }
        }

        return parent::prepareExceptionViewModel($e);
    }
}
