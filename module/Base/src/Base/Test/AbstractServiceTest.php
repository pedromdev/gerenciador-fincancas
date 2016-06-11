<?php

namespace Base\Test;

/**
 *
 * @author PedromDev
 */
abstract class AbstractServiceTest extends \PHPUnit_Framework_TestCase implements
    ServiceTestInterface
{
    /**
     *
     * @var string
     */
    private $serviceName = "";
    
    /**
     *
     * @var string
     */
    private $className = "";
    
    /**
     *
     * @var \Zend\ServiceManager\ServiceLocatorInterface
     */
    private $serviceLocator;
    
    protected function setUp()
    {
        $this->initBeforeSetUp();
        parent::setUp();
    }
    
    public function testSeClasseExiste()
    {
        $this->assertTrue(
            class_exists($this->getClassName()),
            sprintf("A classe %s não existe", $this->getClassName())
        );
    }
    
    public function testSeEstaNoServiceLocator()
    {
        $instance = $this->getService();
        $this->assertInstanceOf($this->getClassName(), $instance);
    }
    
    abstract public function getService();
    
    abstract protected function initBeforeSetUp();
    
    /**
     * 
     * @return string
     */
    public function getServiceName()
    {
        return $this->serviceName;
    }

    /**
     * 
     * @return string
     */
    public function getClassName()
    {
        return $this->className;
    }

    /**
     * 
     * @return \Zend\ServiceManager\ServiceLocatorInterface
     */
    public function getServiceLocator()
    {
        return $this->serviceLocator;
    }

    /**
     * 
     * @param string $serviceName
     * @return \Base\Test\AbstractServiceTest
     */
    public function setServiceName($serviceName)
    {
        $this->serviceName = $serviceName;
        return $this;
    }

    /**
     * 
     * @param string $className
     * @return \Base\Test\AbstractServiceTest
     */
    public function setClassName($className)
    {
        $this->className = $className;
        return $this;
    }

    /**
     * 
     * @param \Zend\ServiceManager\ServiceLocatorInterface $serviceLocator
     * @return \Base\Test\AbstractServiceTest
     */
    public function setServiceLocator(\Zend\ServiceManager\ServiceLocatorInterface $serviceLocator)
    {
        $this->serviceLocator = $serviceLocator;
        return $this;
    }
}
