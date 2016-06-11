<?php

namespace ApplicationTest\Form\InputFilter;

/**
 *
 * @author PedromDev
 */
class LoginTest extends \Base\Test\ServiceTest
{
    protected function initBeforeSetUp()
    {
        $this->setClassName(\Application\Form\InputFilter\Login::class)
            ->setServiceName('Application\Form\InputFilter\Login')
            ->setServiceLocator(\ApplicationTest\Bootstrap::getServiceManager());
    }
    
    public function testValidacaoFiltroParaCasoDeSucesso()
    {
        /* @var $inputFilter \Application\Form\InputFilter\Login */
        $inputFilter = $this->getService();
        $inputFilter->setData([
            'username' => 'login_123',
            'password' => '12345678',
        ]);
        $this->assertTrue($inputFilter->isValid(), 'O teste passou não para o usuário e senha informados');
    }
    
    public function testParaCamposVazios()
    {
        /* @var $inputFilter \Application\Form\InputFilter\Login */
        $inputFilter = $this->getService();
        $inputFilter->setData([
            'username' => '',
            'password' => '',
        ]);
        
        $this->assertFalse($inputFilter->isValid(), 'O filtro foi validado mesmo com campos vazios');
        $invalidFieldsNames = $inputFilter->getInvalidInputName();
        $this->assertEquals(2, count($invalidFieldsNames));
        $this->assertTrue(in_array('username', $invalidFieldsNames), 'O campo username foi validado mesmo com valores não aceitos');
        $this->assertTrue(in_array('password', $invalidFieldsNames), 'O campo password foi validado mesmo com valores não aceitos');
    }
    
    public function testParaOCampoUsuarioComCaracteresNaoAceitos()
    {
        /* @var $inputFilter \Application\Form\InputFilter\Login */
        $inputFilter = $this->getService();
        $inputFilter->setData([
            'username' => 'login_123.&/',
            'password' => '12345678',
        ]);
        
        $this->assertFalse($inputFilter->isValid(), 'O filtro foi validado mesmo com o campo usuário com caracteres não aceitos');
        $invalidFieldsNames = $inputFilter->getInvalidInputName();
        $this->assertEquals(1, count($invalidFieldsNames));
        $this->assertTrue(in_array('username', $invalidFieldsNames), 'O campo username foi validado mesmo com valores não aceitos');
    }
    
    public function testCamposValidadosEFiltrados()
    {
        /* @var $inputFilter \Application\Form\InputFilter\Login */
        $inputFilter = $this->getService();
        $inputFilter->setData([
            'username' => 'login<br>_123   ',
            'password' => '     12345678    ',
        ]);
        $this->assertTrue($inputFilter->isValid(), 'O teste passou não para o usuário e senha informados');
        $this->assertEquals(0, count($inputFilter->getInvalidInputName()));
        $this->assertEquals('login_123', $inputFilter->getValue('username'));
        $this->assertEquals('12345678', $inputFilter->getValue('password'));
    }

}
