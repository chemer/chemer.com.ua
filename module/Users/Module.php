<?php

namespace Users;

use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\Mvc\MvcEvent;

class Module implements AutoloaderProviderInterface
{
    public function onBootstrap(MvcEvent $e)
    {
        $serviceManager = $e->getApplication()->getServiceManager(); 
        $hasLogged      = $serviceManager->get('auth_storage')->hasLogged();
        $viewModel      = $e->getApplication()->getMvcEvent()->getViewModel();

        $viewModel->setVariable('hasLogged', $hasLogged);
    }
    
    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\ClassMapAutoloader' => array(
                __DIR__ . '/autoload_classmap.php',
            ),
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
		    // if we're in a namespace deeper than one level we need to fix the \ in the path
                    __NAMESPACE__ => __DIR__ . '/src/' . str_replace('\\', '/' , __NAMESPACE__),
                ),
            ),
        );
    }

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }
    
    public function getControllerConfig()
    {
        return array(
            'factories' => array(
                'user_controller' => function($cm) {
                    $sm = $cm->getServiceLocator();
                    return new Controller\UserController(
                        $sm->get('user_service')
                    );
                }
            ),
        );
    }
    
    public function getControllerPluginConfig()
    {
        
    }
    
    public function getServiceConfig()
    {
        return array(
            'factories' => array(
                'Zend\Db\Adapter\Adapter' => 'Zend\Db\Adapter\AdapterServiceFactory',
                'user_service' => function ($sm) {
                    return new Service\UserService(
                        $sm->get('db_manager'),
                        $sm->get('auth_storage')
                    );
                },
                'db_manager' => function ($sm) {
                    return new Mapper\DbManager(
                        $sm->get('Zend\Db\Adapter\Adapter')
                    );
                },
                'register_filter' => function ($sm) {
                    return new Form\RegisterFilter(
                        $sm->get('no_record_exists')
                    );
                },
                'no_record_exists' => function ($sm) {
                    return new Validator\NoRecordExists(
                        $sm->get('user_service')
                    );
                },
                'auth_storage' => function ($sm) {
                    return new Storage\Authentication(
                        $sm->get('db_manager')
                    );
                },
            ),
        );
    }
}
