<?php
/**
 * ZF 1 -> ZF 2 migration
 *
 * Example of a custom view helper.
 *
 * @author Bart McLeod (mcleod@spaceweb.nl)
 */
namespace Book\View\Helper;
use Zend\View\Helper\HelperInterface;
use Zend\View\Renderer\RendererInterface as Renderer;

class Hello implements HelperInterface {
    protected $view;

    public function __invoke(){
        return 'Hello from ViewHelper Hello!';
    }

    public function setView(Renderer $view)
    {
        $this->view = $view;
    }

    public function getView()
    {
        return $this->view;
    }
}
