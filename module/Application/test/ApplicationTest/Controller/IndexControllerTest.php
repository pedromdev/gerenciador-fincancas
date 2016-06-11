<?php

namespace ApplicationTest\Controller;

use \Base\Test\AbstractHttpControllerTestCase;

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
        $this->assertInstanceOf(\Zend\View\Model\ViewModel::class, $actionResult);
        $this->assertInstanceOf(\Application\Form\Login::class, $actionResult->getVariable('loginForm'));
    }
}