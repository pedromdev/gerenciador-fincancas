<?php

namespace Auth\Manager;

/**
 *
 * @author Pedro Marcelo
 */
class AuthManagerConfig {
    
    /**
     *
     * @var array
     */
    private $aliases;
    
    /**
     *
     * @var array
     */
    private $factories;
    
    /**
     *
     * @var array
     */
    private $invokables;
    
    public function __construct(array $config) {
        if (!isset($config['auth_manager'])) {
            throw new \Auth\Exception\ConfigurationNotFoundException("A configuração 'auth_manager' não pode ser encontrada");
        }
        $this->initializeConfiguration($config);
    }
    
    /**
     * 
     * @return array
     */
    public function getAliases() {
        return $this->aliases;
    }

    /**
     * 
     * @return array
     */
    public function getFactories() {
        return $this->factories;
    }

    /**
     * 
     * @return array
     */
    public function getInvokables() {
        return $this->invokables;
    }

    /**
     * 
     * @param array $aliases
     * @return \Auth\Manager\AuthManagerConfig
     */
    public function setAliases(array $aliases) {
        $this->aliases = $aliases;
        return $this;
    }

    /**
     * 
     * @param array $factories
     * @return \Auth\Manager\AuthManagerConfig
     */
    public function setFactories(array $factories) {
        $this->factories = $factories;
        return $this;
    }

    /**
     * 
     * @param array $invokables
     * @return \Auth\Manager\AuthManagerConfig
     */
    public function setInvokables(array $invokables) {
        $this->invokables = $invokables;
        return $this;
    }

    private function initializeConfiguration(array $config) {
        foreach ($config['auth_manager'] as $key => $value) {
            $key = strtr($key, ['_' => ' ']);
            $key = ucwords($key);
            $key = strtr($key, [' ' => '']);
            $method = "set$key";
            
            if (!method_exists($this, $method)) {
                throw new \BadMethodCallException(sprintf(
                    "O método '%s' da class '%s' não existe",
                    $method,
                    __CLASS__
                ));
            }
            $this->$method($value);
        }
    }
}
