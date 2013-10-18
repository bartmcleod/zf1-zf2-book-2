<?php
/**
 * ZF 1 -> ZF 2 migration
 *
 * Example of a custom view helper,
 * that can log messages from within the view.
 *
 * @author Bart McLeod (mcleod@spaceweb.nl)
 */
namespace Book\View\Helper;

use Zend\View\Helper\AbstractHelper;

class Logger extends AbstractHelper {
    protected $logger;

    public function __invoke($message){
        $this->logger->info($message);
    }

    public function setLogger($logger)
    {
        $this->logger = $logger;
    }
}
