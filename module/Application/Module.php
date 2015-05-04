<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application;

use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;
use Zend\Session\Container;
use Zend\Session\Config\SessionConfig;
use Zend\Session\SessionManager;
use Zend\Validator\AbstractValidator;

class Module
{
    public function onBootstrap(MvcEvent $e)
    {
        $this->setAppLanguage();
        $application = $e->getApplication();   
        
        $eventManager        = $application->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);
        
        /*
         * Set language
         */
        $translator = $application->getServiceManager()->get('translator');
        $translator->setLocale(APP_LANGUAGE);
        $translator->addTranslationFile(
                        'phpArray',
                        'vendor/zendframework/zendframework/resources/languages/' . mb_substr(APP_LANGUAGE, 0, 2) . '/Zend_Validate.php',  
                        'default',
                        APP_LANGUAGE
                    );
        $translator->setFallbackLocale('en_US');
        
        AbstractValidator::setDefaultTranslator($translator);
        
        /*
         * Set session config
         */
        $config         = $application->getServiceManager()->get('Config');    
        $sessionConfig  = new SessionConfig();
        $sessionConfig->setOptions($config['session_config']);
        
        $sessionManager = new SessionManager($sessionConfig);
        Container::setDefaultManager($sessionManager);
    }

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
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
    
    private function setAppLanguage()
    {
        if (defined('APP_LANGUAGE')) {
            return true;
        }
        
        $lang   = null;
        $cookie = filter_input(INPUT_COOKIE, 'lang');
        $local  = \Locale::acceptFromHttp(filter_input(INPUT_SERVER , 'HTTP_ACCEPT_LANGUAGE'));
        
        switch ($cookie) {
            case 'ru_RU':
            case 'uk_UA':
            case 'en_US':
                $lang = $cookie;
                break;
            default :
                $lang = $local;
        }
        
        return define('APP_LANGUAGE', $lang);
    }
}
