<?php

namespace AuthTest\Mock;

/**
 *
 * @author Pedro Marcelo
 */
class FakeAdapterFactoryMock implements \Zend\ServiceManager\FactoryInterface {
    
    public function createService(\Zend\ServiceManager\ServiceLocatorInterface $serviceLocator) {
        return new FakeAdapterMock();
    }
}
