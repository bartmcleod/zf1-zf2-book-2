<?php
/**
 * ZF 1 -> ZF 2 migration
 *
 * The core of the Book module: the Module class.
 *
 * This returns all the configuration of the Book module and it
 * wires services and events.
 *
 * @author Bart McLeod (mcleod@spaceweb.nl)
 */
namespace Book;
use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;
use Zend\ModuleManager\ModuleManager as ModuleManager;

require_once 'vendor/FirePHPCore/FirePHP.class.php';
use Zend\Log\Logger;
use Zend\Log\Writer\FirePhp as FirePhpWriter;
use Zend\Log\Writer\FirePhp\FirePhpBridge;
use Zend\Db\Adapter\Adapter;
use Zend\View\HelperPluginManager;

/**
 *
 *
 * @author Bart McLeod (mcleod@spaceweb.nl)
 */
class Module
{
    public function init(ModuleManager $moduleManager)
    {
        // from example by Evan Coury
        $sharedEvents = $moduleManager
        ->getEventManager()
        ->getSharedManager();

        $sharedEvents->attach(__NAMESPACE__, 'dispatch', function($e) {
            // specific to namespace
            $controller = $e->getTarget();
            // now you can use a different key!
            $controller->layout('layout/book');
        });

    }

    public function onBootstrap(MvcEvent $e)
    {
        $eventManager        = $e->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);

        $serviceManager = $e->getApplication()->getServiceManager();
        $config = $serviceManager->get('Config');
        $exceptionStrategy = new ExceptionStrategy();
        $exceptionStrategy->setDisplayExceptions($config['view_manager']['display_exceptions']);
        $exceptionStrategy->setExceptionTemplate($config['view_manager']['exception_template']);
        $eventManager->attachAggregate($exceptionStrategy);

        $logger = $serviceManager->get('Book\Log');

        $eventManager->attach(
            MvcEvent::EVENT_DISPATCH_ERROR,
            function($e) use ($logger){
                $logger->err('A route mismatch occured at ' . $_SERVER['REQUEST_URI']);
            }
        );

        Logger::registerErrorHandler($logger);
        //Logger::registerExceptionHandler($logger);
    }

    public function getServiceConfig()
    {
        return array(
            'factories' => array(
                'translator' => 'Zend\I18n\Translator\TranslatorServiceFactory',
                'Book\Log' => function($serviceManager) {
                    $log = new Logger();
                    $writer = new FirePhpWriter(new FirePhpBridge(new \FirePHP()));
                    $log->addWriter($writer);
                    return $log;
                },
                'Zend\Db\Adapter\Adapter' => function ($sm) {
                    $config = $sm->get('Config');
                    return new Adapter(array(
                            'driver'    => 'pdo_mysql',
                            'database'  => 'book',
                            'username'  => 'root',
                            'password'  => $config['db_password'],
                            'hostname'  => 'localhost',
                        ));
                },
            ),
            'aliases' => array(
                'Book\Db' => 'Zend\Db\Adapter\Adapter',
            ),
        );
    }

    public function getConfig()
    {
        return array(
            'router' => array(
                'routes' => array(
                    'book' => array(
                        'type' => 'Zend\Mvc\Router\Http\Literal',
                        'options' => array(
                            'route'    => '/',
                            'defaults' => array(
                                'controller' => 'Book\Controller\Index',
                                'action'     => 'index',
                            ),
                        ),
                    ),
                    'hello' => array(
                        'type' => 'Zend\Mvc\Router\Http\Literal',
                        'options' => array(
                            'route'    => '/index/hello',
                            'defaults' => array(
                                'controller' => 'Book\Controller\Index',
                                'action'     => 'hello',
                            ),
                        ),
                    ),
                    // this is simpler with a child route
                    'products' => array(
                        'type' => 'Zend\Mvc\Router\Http\Literal',
                        'options' => array(
                            'route'    => '/index/products',
                            'defaults' => array(
                                'controller' => 'Book\Controller\Index',
                                'action'     => 'products',
                            ),
                        ),
                    ),
                    'exception' => array(
                        'type' => 'Zend\Mvc\Router\Http\Literal',
                        'options' => array(
                            'route'    => '/exception',
                            'defaults' => array(
                                'controller' => 'Book\Controller\Index',
                                'action'     => 'exception',
                            ),
                        ),
                    ),
                    'name' => array(
                        'type' => 'Zend\Mvc\Router\Http\Literal',
                        'options' => array(
                            'route'    => '/index/name',
                            'defaults' => array(
                                'controller' => 'Book\Controller\Index',
                                'action'     => 'name',
                            ),
                        ),
                    ),
                    'partial' => array(
                        'type' => 'Zend\Mvc\Router\Http\Literal',
                        'options' => array(
                            'route'    => '/index/partial',
                            'defaults' => array(
                                'controller' => 'Book\Controller\Index',
                                'action'     => 'partial',
                            ),
                        ),
                    ),
                    'nested' => array(
                        'type' => 'Zend\Mvc\Router\Http\Literal',
                        'options' => array(
                            'route'    => '/index/nested',
                            'defaults' => array(
                                'controller' => 'Book\Controller\Index',
                                'action'     => 'nested',
                            ),
                        ),
                    ),
                    'form' => array(
                        'type' => 'Zend\Mvc\Router\Http\Literal',
                        'options' => array(
                            'route'    => '/Form',
                            'defaults' => array(
                                'controller' => 'Book\Controller\Form',
                                'action'     => 'index',
                            ),
                        ),
                        'may_terminate' => true,

                        'child_routes' => array(
                            'filter' => array(
                                'type' => 'Literal',
                                'options' => array(
                                    'route' => '/filter',
                                    'defaults' => array(
                                        'action' => 'filter',
                                    ),
                                ),
                             ),
                        ),
                    ),
                    // example of a segment route, saves a lot of typing:
                    'form2' => array(
                        'type' => 'Zend\Mvc\Router\Http\Segment',
                        'options' => array(
                            'route'    => '/Form2[/:action]',
                            'defaults' => array(
                                'controller' => 'Book\Controller\Form2',
                                'action'     => 'index',
                            ),
                        ),
                    ),
                    // another example of a segment route
                    'person' => array(
                        'type' => 'Zend\Mvc\Router\Http\Segment',
                        'options' => array(
                            'route'    => '/person[/:action[/:id]]',
                            'defaults' => array(
                                'controller' => 'Book\Controller\Person',
                                'action'     => 'index',
                                'id'         => null,
                            ),
                        ),
                    ),
                )
            ),

            'translator' => array(
                'locale' => 'nl_NL',
                'translation_file_patterns' => array(
                    array(
                        'type'     => 'PhpArray',
                        'base_dir' => __DIR__ . '/language',
                        'pattern'  => '%s.php',
                    ),
                ),
            ),

            'view_manager' => array(
                'not_found_template'       => 'error/404',
                'exception_template'       => 'error/index',
                'template_path_stack' => array(
                    __DIR__ . '/view',
                ),
                'template_map'  => array(
                    'products'  => __DIR__ . '/view/book/index/products2.phtml',
                    'form'      => __DIR__ . '/view/book/improved-form/index.phtml',
                    'editform'      => __DIR__ . '/view/book/person/edit.phtml',
                    'error/404'               => __DIR__ . '/view/error/404.phtml',
                    'error/index'             => __DIR__ . '/view/error/index.phtml',
                    //'error' => __DIR__ . '/view/error.phtml',
                ),
                'layout' => 'layout/book',
            ),
            'view_helpers' =>  array(
                'invokables' => array(
                    'hello'   => 'Book\View\Helper\Hello',
                    'helloSimple'   => 'Book\View\Helper\HelloSimple',
                ),
                'factories' => array(
                    'logger' => function(HelperPluginManager $pm) {
                        $sm = $pm->getServiceLocator();
                        $logger = $sm->get('Book\Log');
                        $viewHelper = new View\Helper\Logger();
                        $viewHelper->setLogger($logger);
                        return $viewHelper;
                    }
                ),
            ),
            'controller_plugins' => array(
                'invokables' => array(
                    'controllerName' => 'Book\Controller\Plugin\Name',
                )
            ),
        );
    }

    public function getControllerConfig(\Zend\Di\ServiceLocatorInterface $serviceLocator = null)
    {
        return array(
            'invokables' => array(
                'Book\Controller\Index' => 'Book\Controller\IndexController',
                'Book\Controller\Form' => 'Book\Controller\FormController',
                'Book\Controller\Form2' => 'Book\Controller\ImprovedFormController',
                'Book\Controller\Person' => 'Book\Controller\PersonController',
            ),
        );
    }

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }


    /**
     * @param \Zend\Mvc\MvcEvent $e
     */
    /* This is an example of how to not render layout per module, which we do not use
    public function onBootstrap(\Zend\Mvc\MvcEvent $e)
    {
        $sharedEvents = $e->getApplication()->getEventManager()->getSharedManager();
        $sharedEvents->attach(__NAMESPACE__, 'dispatch', function ($e) {
            $result = $e->getResult();
            $result->setTerminal(true);
        });
    }
    */
}
