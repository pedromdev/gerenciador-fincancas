<?php

namespace Application\Form\InputFilter;

use \Zend\InputFilter\Factory;
use \Zend\Validator\Regex;
use \Zend\Validator\NotEmpty;

/**
 *
 * @author PedromDev
 */
class Login extends AbstractInputFilter
{
    public function initValidators() {
        $factory = new Factory();
        
        $this->add($factory->createInput(array(
            'name' => 'username',
            'required' => true,
            'filters' => array(
                array('name' => 'StripTags'),
                array('name' => 'StringTrim'),
            ),
            'validators' => array(
                array(
                    'name' => 'NotEmpty',
                    'break_chain_on_failure' => true,
                    'options' => array(
                        'messages' => array(
                            NotEmpty::INVALID => "O campo de usuário é obrigatório",
                            NotEmpty::IS_EMPTY => "O campo de usuário é obrigatório",
                        ),
                    ),
                ),
                array(
                    'name' => 'Regex',
                    'break_chain_on_failure' => true,
                    'options' => array(
                        'pattern' => '/^[a-z-0-9_]{1,}$/',
                        'messages' => array(
                            Regex::INVALID => 'O campo do usuário aceita somente caracteres minúsculos, números e/ou sublinhado (_)',
                            Regex::NOT_MATCH => 'O campo do usuário aceita somente caracteres minúsculos, números e/ou sublinhado (_)',
                        ),
                    ),
                ),
            ),
        )));
        
        $this->add($factory->createInput(array(
            'name' => 'password',
            'required' => true,
            'filters' => array(
                array('name' => 'StringTrim'),
            ),
            'validators' => array(
                array(
                    'name' => 'NotEmpty',
                    'break_chain_on_failure' => true,
                    'options' => array(
                        'messages' => array(
                            NotEmpty::INVALID => "O campo de senha é obrigatório",
                            NotEmpty::IS_EMPTY => "O campo de senha é obrigatório",
                        ),
                    ),
                ),
            ),
        )));
    }
}
