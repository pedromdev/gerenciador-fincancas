<?php

namespace Application\Form;

use \Zend\ServiceManager\FactoryInterface;
use \Zend\ServiceManager\ServiceLocatorInterface;

/**
 *
 * @author PedromDev
 */
class LoginFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $loginForm = new Login();
        $loginForm->setInputFilter($serviceLocator->get('Application\Form\InputFilter\Login'));
        return $loginForm;
    }
}
