<?php

namespace Auth\Manager;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 *
 * @author Pedro Marcelo
 */
class AuthManagerConfigFactory implements FactoryInterface {
    
    public function createService(ServiceLocatorInterface $serviceLocator) {
        $config = $serviceLocator->get('Config');
        $authConfig = new AuthManagerConfig($config);
        return $authConfig;
    }

}
