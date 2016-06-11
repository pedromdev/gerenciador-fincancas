<?php

namespace Base\Test;

/**
 *
 * @author PedromDev
 */
abstract class ServiceTest extends AbstractServiceTest
{   
    public function getService()
    {
        return $this->getServiceLocator()->get($this->getServiceName());
    }
}
