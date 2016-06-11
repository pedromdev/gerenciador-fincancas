<?php

namespace Auth\Manager;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 *
 * @author Pedro Marcelo
 */
class AuthManagerFactory implements FactoryInterface {
    
    public function createService(ServiceLocatorInterface $serviceLocator) {
        $authManager = new AuthManager($serviceLocator);
        return $authManager;
    }
}
