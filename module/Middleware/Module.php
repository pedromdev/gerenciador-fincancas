<?php

namespace Middleware;

use Zend\ModuleManager\Feature\ConfigProviderInterface;
use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use \Zend\ModuleManager\Feature\InitProviderInterface;
use \Zend\ModuleManager\ModuleManagerInterface;

/**
 *
 * @author PedromDev
 */
class Module implements
    ConfigProviderInterface,
    AutoloaderProviderInterface,
    InitProviderInterface
{
    public function init(ModuleManagerInterface $manager)
    {
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

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }
}
