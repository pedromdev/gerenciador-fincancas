<?php

namespace ApplicationTest\Form;

/**
 *
 * @author PedromDev
 */
class LoginTest extends \Base\Test\ServiceTest
{
    protected function initBeforeSetUp()
    {
        $this->setClassName(\Application\Form\Login::class)
            ->setServiceName('Application\Form\Login')
            ->setServiceLocator(\ApplicationTest\Bootstrap::getServiceManager());
    }
    
    public function testInputFilterEmUsoParaOFormularioDeLogin()
    {
        /* @var $loginForm \Application\Form\Login */
        $loginForm = $this->getService();
        $this->assertInstanceOf(\Application\Form\InputFilter\Login::class, $loginForm->getInputFilter());
    }
}
