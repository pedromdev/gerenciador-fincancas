<?php

namespace AuthTest\Mock;

/**
 *
 * @author Pedro Marcelo
 */
class AuthAdapterFactoryMock implements \Zend\ServiceManager\FactoryInterface {
    
    public function createService(\Zend\ServiceManager\ServiceLocatorInterface $serviceLocator) {
        return new AuthAdapterMock();
    }
}
