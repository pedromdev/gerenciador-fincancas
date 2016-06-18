<?php

namespace Application\Form;

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
        $signup = new Signup();
        $signup->setInputFilter($serviceLocator->get('Application\Form\InputFilter\Signup'));
        return $signup;
    }
}
