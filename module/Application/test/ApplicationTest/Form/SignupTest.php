<?php

namespace ApplicationTest\Form;

/**
 *
 * @author PedromDev
 */
class SignupTest extends \Base\Test\ServiceTest
{
    protected function initBeforeSetUp()
    {
        $this->setClassName(\Application\Form\Signup::class)
            ->setServiceName('Application\Form\Signup')
            ->setServiceLocator(\ApplicationTest\Bootstrap::getServiceManager());
    }
    
    public function testInputFilterEmUsoParaOFormularioDeCadastro()
    {
        /* @var $signupForm \Application\Form\Signup */
        $signupForm = $this->getService();
        $this->assertInstanceOf(\Application\Form\InputFilter\Signup::class, $signupForm->getInputFilter());
    }
}
