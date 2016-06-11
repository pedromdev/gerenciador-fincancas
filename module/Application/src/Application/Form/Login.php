<?php

namespace Application\Form;

use \Zend\Form\Form;

/**
 *
 * @author PedromDev
 */
class Login extends Form
{
    public function __construct()
    {
        parent::__construct('login', []);
        $this->setAttribute('class', 'form-signin')
            ->setAttribute('method', 'POST')
            ->setAttribute('id', 'login-form');
        
        $this->add(array(
            'name' => 'username',
            'type' => 'Text',
            'attributes' => array(
                'id' => 'username',
                'class' => 'form-control',
                'placeholder' => 'Usuário',
                'required' => true,
            ),
        ));
        
        $this->add(array(
            'name' => 'password',
            'type' => 'Password',
            'attributes' => array(
                'id' => 'password',
                'class' => 'form-control',
                'placeholder' => 'Senha',
                'required' => true,
            ),
        ));
    }
}
