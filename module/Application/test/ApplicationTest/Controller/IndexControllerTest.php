<?php

namespace ApplicationTest\Controller;

use \Base\Test\AbstractHttpControllerTestCase;
use \Zend\View\Model\ViewModel;

/**
 *
 * @author PedromDev
 */
class IndexControllerTest extends AbstractHttpControllerTestCase
{
    protected function initBeforeSetUp()
    {
        $this->setServiceLocator(\ApplicationTest\Bootstrap::getServiceManager());
    }
    
    public function testPaginaInicial()
    {
        $this->dispatch('/', 'GET');
        $actionResult = $this->getActionResult();
        $this->assertActionName('index');
        $this->assertMatchedRouteName('home');
        $this->assertInstanceOf(ViewModel::class, $actionResult);
        $this->assertInstanceOf(\Application\Form\Login::class, $actionResult->getVariable('loginForm'));
    }
    
    public function testPaginaDeCadastroDeUsuario()
    {
        $this->dispatch('/signup', 'GET');
        $actionResult = $this->getActionResult();
        $this->assertActionName('signup');
        $this->assertMatchedRouteName('signup');
        $this->assertInstanceOf(ViewModel::class, $actionResult);
        $this->assertInstanceOf(\Application\Form\Signup::class, $actionResult->getVariable('signupForm'));
    } 
}