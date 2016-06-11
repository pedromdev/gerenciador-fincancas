<?php

namespace Base\Test;

/**
 *
 * @author PedromDev
 */
interface ServiceTestInterface
{
    abstract public function testSeClasseExiste();
    
    abstract public function testSeEstaNoServiceLocator();
}
