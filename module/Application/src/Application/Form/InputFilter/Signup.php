<?php

namespace Application\Form\InputFilter;

use \Zend\Validator\NotEmpty;
use \Zend\Validator\EmailAddress;
use \Zend\Validator\Identical;
use \Zend\Validator\StringLength;
use \Doctrine\ORM\EntityManager;
use \Zend\InputFilter\BaseInputFilter;

/**
 *
 * @author PedromDev
 */
class Signup extends AbstractDoctrineInputFilter
{
    public function __construct(EntityManager $entityManager, BaseInputFilter $inputFilter = null)
    {
        if (!is_null($inputFilter)) {
            $this->merge($inputFilter);
        }
        parent::__construct($entityManager);
    }
    public function initValidators()
    {
        $this->add(array(
            'name' => 'email',
            'required' => true,
            'filters' => array(
                array('name' => 'StripTags'),
                array('name' => 'StringTrim'),
            ),
            'validators' => array(
                array(
                    'name' => 'EmailAddress',
                    'break_chain_on_failure' => true,
                    'options' => array(
                        'messages' => array(
                            EmailAddress::INVALID => "O e-mail informado está inválido",
                            EmailAddress::INVALID_FORMAT => "O e-mail informado está inválido",
                        ),
                    ),
                ),
                array(
                    'name' => 'NotEmpty',
                    'break_chain_on_failure' => true,
                    'options' => array(
                        'messages' => array(
                            NotEmpty::INVALID => "O campo de e-mail é obrigatório",
                            NotEmpty::IS_EMPTY => "O campo de e-mail é obrigatório",
                        ),
                    ),
                ),
            ),
        ));
        
        $this->add(array(
            'name' => 'confirm_password',
            'required' => true,
            'filters' => array(
                array('name' => 'StripTags'),
            ),
            'validators' => array(
                array(
                    'name' => 'Identical',
                    'break_chain_on_failure' => true,
                    'options' => array(
                        'token' => 'password',
                        'messages' => array(
                            Identical::NOT_SAME => "A confirmação de senha não bate com a senha informada",
                        ),
                    ),
                ),
            ),
        ));
        
        $this->add(array(
            'name' => 'full_name',
            'required' => true,
            'filters' => array(
                array('name' => 'StripTags'),
                array('name' => 'StringTrim'),
            ),
            'validators' => array(
                array(
                    'name' => 'StringLength',
                    'break_chain_on_failure' => true,
                    'options' => array(
                        'min' => 4,
                        'max' => 150,
                        'messages' => array(
                            StringLength::TOO_SHORT => "O nome completo deve ter pelo menos 4 letras",
                            StringLength::TOO_LONG => "O nome completo deve ter no máximo 150 caracteres",
                        ),
                    ),
                ),
            ),
        ));
    }
}
