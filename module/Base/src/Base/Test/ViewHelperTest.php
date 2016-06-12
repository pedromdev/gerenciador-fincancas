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
    public function setServiceLocator(\Zend\ServiceManager\ServiceLocatorInterface $serviceLocator)
    {
        print get_class($serviceLocator);
        if (!$serviceLocator instanceof \Zend\ServiceManager\ServiceLocatorAwareInterface) {
            throw new \InvalidArgumentException(
                "O argumento passado deve ser uma instância de \Zend\ServiceManager\ServiceManagerAwareInterface"
            );
        }
        return parent::setServiceLocator($serviceLocator);
    }

    public function getService()
    {
        return $this->getServiceLocator()->getServiceLocator()->get($this->getServiceName());
    }
}
