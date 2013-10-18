<?php
/**
 * ZF 1 -> ZF 2 migration
 *
 * Example of a custom controller plugin.
 * Controller plugins replace the action helpers from ZF 1.
 *
 * @author Bart McLeod (mcleod@spaceweb.nl)
 */
namespace Book\Controller\Plugin;


use Zend\Mvc\Controller\Plugin\AbstractPlugin;

/**
 * Class Name
 *
 * It returns the name of the controller class.
 * This is useless, but it demonstrates that this plugin has
 * access to the controller that uses it.
 *
 * @package Book\Controller\Plugin
 */
class Name extends AbstractPlugin
{
    public function __invoke()
    {
        $controller = $this->getController();
        return get_class($controller);
    }
}
