<?php

namespace Middleware\Exception;

/**
 *
 * @author PedromDev
 */
class HttpFilterException extends \Exception
{
    const IS_NOT_XML_HTTP_REQUEST = 600;
    
    const HTTP_METHOD_NOT_ACCEPTED = 601;
    
    public static function isNotXmlHTTPRequest($routeName)
    {
        return new self("The '$routeName' route accept XML HTTP requests only.", self::IS_NOT_XML_HTTP_REQUEST);
    }
    
    public static function httpMethodNotAccepted($routeName, $httpMethod, array $acceptedMethods)
    {
        return new self(sprintf(
            "The '$routeName' route not accept '%s' method. Accepted methods: %s",
            strtoupper($httpMethod),
            empty($acceptedMethods)? 'none' : implode(', ', $acceptedMethods)
        ), self::HTTP_METHOD_NOT_ACCEPTED);
    }
}