<?php

namespace Application\Form;

use \Zend\Form\Form;

/**
 *
 * @author PedromDev
 */
class Signup extends Form
{
    public function __construct()
    {
        parent::__construct('signup', []);
        $this->setAttribute('class', 'form-signin')
            ->setAttribute('method', 'POST')
            ->setAttribute('id', 'signup-form');
        
        
        $this->add(array(
            'name' => 'username',
            'type' => 'Text',
            'attributes' => array(
                'id' => 'username',
                'class' => 'form-control',
                'placeholder' => 'Informe o seu nome de usuário',
                'required' => true,
            ),
        ));
        
        $this->add(array(
            'name' => 'password',
            'type' => 'Password',
            'attributes' => array(
                'id' => 'password',
                'class' => 'form-control',
                'placeholder' => 'Informe a sua senha',
                'required' => true,
            ),
        ));
    }
}
