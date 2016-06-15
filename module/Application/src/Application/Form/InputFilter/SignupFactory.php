<?php

namespace Application\Form\InputFilter;

use \Zend\ServiceManager\FactoryInterface;
use \Zend\ServiceManager\ServiceLocatorInterface;

/**
 *
 * @author PedromDev
 */
class SignupFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $signup = new Signup(
            $serviceLocator->get('Doctrine\ORM\EntityManager'),
            $serviceLocator->get('Application\Form\InputFilter\Login')
        );
        return $signup;
    }
}
