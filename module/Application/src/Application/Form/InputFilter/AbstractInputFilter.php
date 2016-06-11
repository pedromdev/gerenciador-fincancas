<?php

namespace Application\Form\InputFilter;

/**
 *
 * @author PedromDev 
 */
abstract class AbstractInputFilter extends \Zend\InputFilter\InputFilter
{
    public function __construct()
    {
        $this->initValidators();
    }
    /**
     * Retorna uma lista dos nomes dos campos inválidos
     * 
     * @return array
     */
    public function getInvalidInputName()
    {
        $inputs = $this->getInvalidInput();
        $array = [];
        
        if (!empty($inputs)) {
            $array = array_keys($inputs);
        }
        return $array;
    }
    
    abstract public function initValidators();
}
