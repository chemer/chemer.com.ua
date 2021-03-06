<?php

namespace Feedback;

use Zend\ModuleManager\Feature\AutoloaderProviderInterface;

class Module implements AutoloaderProviderInterface
{   
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
                'feedback_controller' => function($cm) {
                    $sm = $cm->getServiceLocator();
                    return new Controller\FeedbackController(
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
            
        );
    }
}
