<?php

namespace Base\Test;

use \Zend\View\Model\JsonModel;

/**
 *
 * @author PedromJava
 */
abstract class AbstractXMLHttpRequestControllerTestCase extends AbstractHttpControllerTestCase
{
    
    /**
     * Esta função já está com o parâmetro $isXmlHttpRequest especificado como true
     * 
     * @param  string      $url
     * @param  string|null $method
     * @param  array|null  $params
     */
    public function dispatch($url, $method = null, $params = array())
    {
        parent::dispatch($url, $method, $params, true);
    }
    
    /**
     * 
     * @param string $name Nome do parâmetro nas variáveis do JsonModel
     * @param string $expected Valor esperado da variável
     */
    public function assertJsonModelVariable($name, $expected)
    {
        $model = $this->getJsonModel();
        $this->assertEquals($expected, $model->getVariable($name));
    }
    
    /**
     * 
     * @param string $name Nome do parâmetro nas variáveis do JsonModel
     */
    public function assertContainsJsonModelVariable($name)
    {
        $model = $this->getJsonModel();
        $this->assertNotNull($model->getVariable($name));
    }
    
    /**
     * 
     * @param string $name
     * @param mixed $expected
     * @throws Exception
     */
    public function assertContainsValueOnJsonModelVariable($name, $expected)
    {
        $model = $this->getJsonModel();
        $value = $model->getVariable($name);
        
        if (is_null($value))
        {
            throw new \Exception("A variável '$name' não existe no JsonModel");
        }
        
        if (is_array($value))
        {
            $this->assertTrue(in_array($expected, $value));
        }
        else if (is_string($value) && is_string($expected))
        {
            $this->assertTrue(stripos($value, $expected) !== false);
        }
        else
        {
            $this->fail("O valor retornado da variável não um Array ou uma String");
        }
    }
    
    public function assertJsonModelVariableType($name, $expectedType)
    {
        $types = [
            'bool',
            'int',
            'string',
            'float',
            'array',
            'object',
            'null',
        ];
        $model = $this->getJsonModel();
        $value = $model->getVariable($name);
        
        if (is_string($expectedType))
        {
            if (in_array($expectedType, $types))
            {
                $this->assertTrue(call_user_func("is_$expectedType", $value));
            }
            else if (class_exists($expectedType))
            {
                $this->assertInstanceOf($expectedType, $value);
            }
            else
            {
                $this->fail('O parâmetro $expectedType não é um tipo a ser testado ou não é uma classe existente');
            }
        }
        else
        {
            $this->fail('O parâmetro $expectedType deve ser uma string que contenha o tipo a ser testado ou o nome de uma classe');
        }
    }
    
    /**
     * Retorna uma instância de \Zend\View\Model\JsonModel
     * 
     * @return JsonModel
     * @throws Exception
     */
    protected function getJsonModel()
    {
        /* @var $mvcEvent \Zend\Mvc\MvcEvent */
        $mvcEvent = $this->getApplication()->getMvcEvent();
        
        if (is_null($mvcEvent))
        {
            throw new \Exception('Instância do MvcEvent não pôde ser encontrada');
        }
        $model = $mvcEvent->getViewModel();
        
        if (!($model instanceof JsonModel))
        {
            throw new \Exception('O model retornado não é uma instância de \Zend\View\Model\JsonModel');
        }
        return $model;
    }
}
