<?php

namespace Base\Test;

/**
 *
 * @author PedromDev
 */
abstract class ViewHelperTest extends AbstractServiceTest
{   
    /**
     * 
     * @return \Zend\ServiceManager\ServiceLocatorAwareInterface
     */
    public function getServiceLocator()
    {
        return parent::getServiceLocator();
    }
    
    /**
     * 
     * @param \Zend\ServiceManager\ServiceManagerAwareInterface $serviceLocator
     * @return ViewHelperTest
     */
    public function setServiceLocator(\Zend\ServiceManager\ServiceManagerAwareInterface $serviceLocator)
    {
        return parent::setServiceLocator($serviceLocator);
    }

    public function getService()
    {
        return $this->getServiceLocator()->getServiceLocator()->get($this->getServiceName());
    }
}
