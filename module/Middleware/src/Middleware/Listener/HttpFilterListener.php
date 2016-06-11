<?php

namespace Middleware\Listener;

use \Zend\EventManager\AbstractListenerAggregate;
use \Zend\EventManager\EventManagerInterface;
use \Zend\Mvc\MvcEvent;
use \Middleware\Exception\HttpFilterException;

/**
 *
 * @author PedromDev
 */
class HttpFilterListener extends AbstractListenerAggregate
{
    public function attach(EventManagerInterface $events)
    {
        if (php_sapi_name() == 'cli') {
            return;
        }
        $this->listeners[] = $events->attach(MvcEvent::EVENT_DISPATCH, [$this, 'checkXmlHttpRequest'], 9999);
        $this->listeners[] = $events->attach(MvcEvent::EVENT_DISPATCH, [$this, 'checkHttpMethod'], 9998);
    }
    
    public function checkXmlHttpRequest(MvcEvent $event)
    {
        $routeOptions = $event->getParam('route-options', []);
        
        if (!isset($routeOptions['http']['only_xml_http_request'])) {
            return;
        }
        
        if ($routeOptions['http']['only_xml_http_request'] && !$event->getRequest()->isXmlHttpRequest()) {
            throw HttpFilterException::isNotXmlHTTPRequest($event->getRouteMatch()->getMatchedRouteName());
        }
    }
    
    public function checkHttpMethod(MvcEvent $event)
    {
        $routeOptions = $event->getParam('route-options', []);
        
        if (!isset($routeOptions['http']['accepted_methods']) || !is_array($routeOptions['http']['accepted_methods'])) {
            return;
        }
        
        $acceptedMethods = $routeOptions['http']['accepted_methods'];
        
        foreach ($acceptedMethods as $method => $flag) {
            $method = ucfirst(strtolower($method));
            $result = call_user_func([$event->getRequest(), "is$method"]);
            
            if (($flag && $result) || (!$flag && !$result)) {
                continue;
            }
            
            $acceptedMethods = array_keys($acceptedMethods, true);
            throw HttpFilterException::httpMethodNotAccepted(
                $event->getRouteMatch()->getMatchedRouteName(),
                $method,
                $acceptedMethods
            );
        }
    }
}
