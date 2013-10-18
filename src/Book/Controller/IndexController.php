<?php
/**
 * ZF 1 -> ZF 2 migration
 *
 * The index controller.
 * - displays the homepage of the book module
 * - demonstrates a custom exception strategy
 * - demonstrates the usage of a controller plugin
 * - demonstrates the use of nested view models
 * - demonstrates the use of a partial
 * - demonstrates using a different view template
 * - demonstrates the dispatch event hook
 *
 *
 * @author Bart McLeod (mcleod@spaceweb.nl)
 */
namespace Book\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Stdlib\RequestInterface;
use Zend\Stdlib\ResponseInterface;
use Zend\Session\Container as SessionContainer;

class IndexController extends AbstractActionController
{
    /**
     * Example implementation of the dispatch event hook.
     *
     * @param RequestInterface $request
     * @param ResponseInterface $response
     * @return mixed|ResponseInterface
     */
    public function dispatch(RequestInterface $request, ResponseInterface $response = null)
    {
        $session = new SessionContainer('Frontend_2');
        $session->hello = 'The session says hello 2!';
        return parent::dispatch($request, $response);
    }

    /**
     * The default action
     *
     * Go to / to see this in action.
     *
     * @return array|void
     */
    public function indexAction()
    {
        // will display the results of the dispatch hook
    }

    /**
     * Go to /index/hello to see this in action
     *
     * @return ViewModel
     */
    public function helloAction()
    {
        $session = new SessionContainer('Frontend_2');
        return new ViewModel(array('hello' => $session->hello));
    }

    /**
     * Go to /exception to see this in action
     *
     * @throws \Exception
     */
    public function exceptionAction()
    {
        throw new \Exception("This exception is thrown just for testing exceptions.");
    }

    /**
     * Go to /index/products to see this in action
     * @return ViewModel
     */
    public function productsAction()
    {
        // option 1: returning an associative array
        //return array('products' => array('orange', 'kiwi'));

        // option 2: returning a ViewModel instantiated with an array
        //return new ViewModel(array('products' => array('orange', 'kiwi')));

        // option 3: create a ViewModel and populate it using overloading
        $view = new ViewModel();
        $view->products = array('orange', 'kiwi');

        // disable layout for this action:
        //$view->setTerminal(true);

        // change the template for this action:
        // this will render products2.phtml (the alternative script)
        // it is configured in the template_map key of the view_manager
        $view->setTemplate('products');
        return $view;
    }

    /**
     * Demonstrates the Name controller plugin (it replace the action helper)
     *
     * Go to /index/name to see it in action.
     *
     * @return array
     */
    public function nameAction()
    {
        return array(
            'controllerName' => $this->controllerName(),
        );
    }

    /**
     * The view uses a partial
     *
     * Go to /index/partial to see it in action.
     */
    public function partialAction()
    {
        // demo code is in the template
    }

    /**
     * Returns a view model with a nested view model that renders a partial.
     *
     * Go to /index/nested to see it in action.
     *
     * @return ViewModel
     */
    public function nestedAction()
    {
        $view = new ViewModel();
        $partialView = new ViewModel();
        $partialView->setTemplate('book/index/partials/the-partial.phtml');
        $partialView->variable = 'Nested model has a variable!';
        $view->addChild($partialView, 'nested');
        return $view;
    }
}
