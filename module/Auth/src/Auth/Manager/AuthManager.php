<?php

namespace Auth\Manager;

use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Auth\Exception\InvalidAuthAdapterException;
use Auth\Adapter\AuthAdapterInterface;
/**
 *
 * @author Pedro Marcelo
 */
class AuthManager implements ServiceLocatorInterface, ServiceLocatorAwareInterface {
    
    /**
     *
     * @var boolean
     */
    private $allowOverride = false;
    
    /**
     *
     * @var AuthManagerConfig
     */
    private $authConfig;
    
    /**
     *
     * @var ServiceLocatorInterface
     */
    private $parentLocator;
    
    /**
     *
     * @var AuthAdapterInterface[]
     */
    private $instances = [];
    
    public function __construct(ServiceLocatorInterface $serviceLocator) {
        $this->setAuthConfig($serviceLocator->get('AuthManagerConfig'));
        $this->parentLocator = $serviceLocator;
    }
    
    /**
     * 
     * @return AuthManagerConfig
     */
    public function getAuthConfig() {
        return $this->authConfig;
    }

    /**
     * 
     * @param \Auth\Manager\AuthManagerConfig $authConfig
     * @return \Auth\Manager\AuthManager
     */
    public function setAuthConfig(AuthManagerConfig $authConfig) {
        $this->authConfig = $authConfig;
        return $this;
    }

    /**
     * {@inheritDoc}
     * 
     * @param string $name
     * @return AuthAdapterInterface
     */
    public function get($name) {
        if ($this->hasInstance($name)) {
            return $this->getInstance($name);
        }
        $realName = $this->getNameFromAlias($name);
        $instance = $this->getFromFactories($realName);
        
        if (!$instance) {
            $instance = $this->getFromInvokables($realName);
        }
        
        if (!is_object($instance)) {
            throw new \Zend\ServiceManager\Exception\ServiceNotFoundException(sprintf(
                "Uma instância em '%s' não pode ser encontrada",
                $realName
            ));
        } else if (!($instance instanceof AuthAdapterInterface)) {
            throw new InvalidAuthAdapterException(sprintf(
                "A instância retornada em '%s' não é uma instância de de \Auth\Adapter\AuthAdapterInterface",
                $realName
            ));
        }
        $this->setInstance($realName, $instance);
        return $instance;
    }

    public function has($name) {
        
    }

    /**
     * 
     * @return ServiceLocatorInterface
     */
    public function getServiceLocator() {
        return $this->parentLocator;
    }

    /**
     * 
     * @param ServiceLocatorInterface $serviceLocator
     * @return \Auth\Manager\AuthManager
     */
    public function setServiceLocator(ServiceLocatorInterface $serviceLocator) {
        $this->parentLocator = $serviceLocator;
        return $this;
    }
    
    /**
     * Checa se existe uma factory com o name especificado
     * 
     * @param string $name
     * @return boolean
     */
    private function hasFactory($name) {
        $factories = $this->getAuthConfig()->getFactories();
        return isset($factories[$name]);
    }
    
    /**
     * Retorna uma fábrica
     * 
     * @param string $name
     * @return mixed
     */
    private function getFactory($name) {
        $factory = null;
        if ($this->hasFactory($name)) {
            $factories = $this->getAuthConfig()->getFactories();
            $factory = $factories[$name];
        }
        return $factory;
    }
    
    /**
     * Retorna o resultado da factory, caso ela exista.
     * 
     * @param string $name
     * @return mixed
     */
    private function getFromFactories($name) {
        $instance = null;
        $factory = $this->getFactory($name);
        
        if (is_string($factory)) {
            if (class_exists($factory)) {
                $factory = new $factory();
            }
            
            if ($factory instanceof \Zend\ServiceManager\FactoryInterface) {
                $instance = $factory->createService($this->getServiceLocator());
            }
        } else if (is_callable($factory)) {
            $instance = call_user_func($factory, $this->getServiceLocator());
        }
        return $instance;
    }
    
    /**
     * Verifica se existe um invokable
     * 
     * @param string $name
     * @return boolean
     */
    private function hasInvokable($name) {
        $invokables = $this->getAuthConfig()->getInvokables();
        return isset($invokables[$name]);
    }
    
    /**
     * Retorna um invokable
     * 
     * @param string $name
     * @return string
     */
    private function getInvokable($name) {
        $invokable = '';
        
        if ($this->hasInvokable($name)) {
            $invokables = $this->getAuthConfig()->getInvokables();
            $invokable = preg_replace("/^([a-zA-Z])/", "\\\\$1", $invokables[$name]);
        }
        return $invokable;
    }
    
    /**
     * Retorna o resultado de um invokable, caso exista
     * 
     * @param string $name
     * @return mixed
     */
    private function getFromInvokables($name) {
        $instance = null;
        $invokable = $this->getInvokable($name);
        
        if (class_exists($invokable)) {
            $instance = new $invokable();
        }
        return $instance;
    }
    
    /**
     * Verifica se existe um alias
     * 
     * @param string $name
     * @return boolean
     */
    private function hasAlias($name) {
        $aliases = $this->getAuthConfig()->getAliases();
        return isset($aliases[$name]);
    }
    
    /**
     * Retorna um name que possua um alias
     * 
     * @param string $name
     * @return string
     */
    private function getNameFromAlias($name) {
        if ($this->hasAlias($name)) {
            $aliases = $this->getAuthConfig()->getAliases();
            
            if (is_string($aliases[$name])) {
                $name = $aliases[$name];
            }
        }
        return $name;
    }
    
    /**
     * Verifica se existe laguma instância que já foi criada
     * 
     * @param string $name
     * @return boolean
     */
    public function hasInstance($name) {
        $realName = $this->getNameFromAlias($name);
        return isset($this->instances[$realName]);
    }
    
    /**
     * Retorna uma instância caso já tenha sido criada
     * 
     * @param string $name
     * @return AuthAdapterInterface|null
     */
    public function getInstance($name) {
        $realName = $this->getNameFromAlias($name);
        
        if ($this->hasInstance($realName)) {
            return $this->instances[$realName];
        }
        return null;
    }
    
    /**
     * Registra uma instância para determinado name. Caso já exista um name, a instância só poderá
     * ser registrada caso $allowOverride seja true.
     * 
     * @param string $name
     * @param AuthAdapterInterface $instance
     * @return \Auth\Manager\AuthManager
     */
    public function setInstance($name, AuthAdapterInterface $instance) {
        if (!$this->hasInstance($name) || $this->getAllowOverride() == true) {
            $this->instances[$name] = $instance;
        }
        return $this;
    }
    
    /**
     * 
     * @return boolean
     */
    public function getAllowOverride() {
        return $this->allowOverride;
    }

    /**
     * 
     * @param boolean $allowOverride
     * @return \Auth\Manager\AuthManager
     */
    public function setAllowOverride($allowOverride) {
        if (is_bool($allowOverride)) {
            $this->allowOverride = $allowOverride;
        }
        return $this;
    }
}
