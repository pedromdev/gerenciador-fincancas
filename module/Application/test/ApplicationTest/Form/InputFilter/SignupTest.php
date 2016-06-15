<?php

namespace ApplicationTest\Form\InputFilter;

/**
 *
 * @author PedromDev
 */
class SignupTest extends \Base\Test\ServiceTest
{
    protected function initBeforeSetUp()
    {
        $this->setClassName(\Application\Form\InputFilter\Signup::class)
            ->setServiceName('Application\Form\InputFilter\Signup')
            ->setServiceLocator(\ApplicationTest\Bootstrap::getServiceManager());
    }
    
    public function testSePossuiCamposHerdadosDeLogin()
    {
        $service = $this->getService();
        $this->assertTrue($service->has('username'), "O input filter não possui o campo 'username'");
        $this->assertTrue($service->has('password'), "O input filter não possui o campo 'password'");
    }
    
    public function testFalhaAoConfirmarSenha()
    {
        $service = $this->getService();
        $service->setData([
            'username' => 'login_123',
            'password' => '12345678',
            'email' => 'webmaster@example.com',
            'full_name' => 'Pedro Marcelo',
            'confirm_password' => '87654321',
        ]);
        $this->assertFalse(
            $service->isValid(),
            "A senha foi confirmada mesmo com valores diferentes"
        );
        $this->assertTrue(
            in_array('confirm_password', $service->getInvalidInputName()),
            "O campo 'confirm_password' não está entre os campos inválidos"
        );
    }
    
    public function testFalhaAoInformarEmailInvalid()
    {
        $service = $this->getService();
        $service->setData([
            'username' => 'login_123',
            'password' => '12345678',
            'email' => 'webmaster',
            'full_name' => 'Pedro Marcelo',
            'confirm_password' => '12345678',
        ]);
        $this->assertFalse(
            $service->isValid(),
            "O e-mail informado foi aceito mesmo estando inválido"
        );
        $this->assertTrue(
            in_array('email', $service->getInvalidInputName()),
            "O campo 'email' não está entre os campos inválidos"
        );
    }
    
    public function testFalhaAoPassarNomeCompletoComMaisDe150Caracteres()
    {
        $service = $this->getService();
        $service->setData([
            'username' => 'login_123',
            'password' => '12345678',
            'email' => 'webmaster@example.com',
            'full_name' => str_repeat("A", 151),
            'confirm_password' => '12345678',
        ]);
        $this->assertFalse(
            $service->isValid(),
            "O nome informado foi aceito mesmo passando do limite de caracteres"
        );
        $this->assertTrue(
            in_array('full_name', $service->getInvalidInputName()),
            "O campo 'full_name' não está entre os campos inválidos"
        );
    }
    
    public function testFalhaAoPassarNomeCompletoComMenosDe4Caracteres()
    {
        $service = $this->getService();
        $service->setData([
            'username' => 'login_123',
            'password' => '12345678',
            'email' => 'webmaster@example.com',
            'full_name' => "AAA",
            'confirm_password' => '12345678',
        ]);
        $this->assertFalse(
            $service->isValid(),
            "O nome informado foi aceito mesmo passando do limite de caracteres"
        );
        $this->assertTrue(
            in_array('full_name', $service->getInvalidInputName()),
            "O campo 'full_name' não está entre os campos inválidos"
        );
    }
}
