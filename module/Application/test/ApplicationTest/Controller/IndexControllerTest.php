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
        $this->assertActionName('index');
        $this->assertMatchedRouteName('home');
        var_dump($this->getResult());
    }
}