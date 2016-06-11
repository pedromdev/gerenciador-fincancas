<?php

namespace Auth;

/**
 *
 * @author Pedro Marcelo
 */
class Module {
    
    public function getConfig() {
        $configFiles = glob(__DIR__ . '/config/module.config{,.local}.php', GLOB_BRACE);
        $config = [];
        foreach ($configFiles as $configFile) {
            $config = \Zend\Stdlib\ArrayUtils::merge($config, include $configFile);
        }
        return $config;
    }

    public function getAutoloaderConfig() {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }
}
