<?php

namespace Middleware\Listener;

use \Zend\EventManager\AbstractListenerAggregate;
use \Zend\EventManager\EventManagerInterface;
use \Zend\Mvc\MvcEvent;
use \Zend\Stdlib\ArrayUtils;

/**
 *
 * @author PedromDev
 */
class RouteOptionsListener extends AbstractListenerAggregate
{
    public function attach(EventManagerInterface $events)
    {
        if (php_sapi_name() == 'cli') {
            return;
        }
        $this->listeners[] = $events->attach(MvcEvent::EVENT_ROUTE, [$this, 'onRoute'], 0);
    }
    
    public function onRoute(MvcEvent $event)
    {
        $matchedRouteName = $event->getRouteMatch()->getMatchedRouteName();
        $matchedRoutes = explode('/', $matchedRouteName);
        $config = $event->getApplication()->getServiceManager()->get('Config');
        $route = ['child_routes' => $config['router']['routes']];
        $routeOptions = [];
        $i = 0;
        
        do {
            $route = $route['child_routes'][$matchedRoutes[$i]];
            $routeOptions = ArrayUtils::merge($routeOptions, $route['options']);
            $i++;
        } while (isset($matchedRoutes[$i]) && isset($route['child_routes']));
        
        $event->setParam('route-options', $routeOptions);
    }
}
