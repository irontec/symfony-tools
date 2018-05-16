<?php

namespace SymfonyTools\Request;

/**
 * @author ddniel16
 */
class Request
{

    /**
     * @throws \Exception
     * @return \Symfony\Component\HttpFoundation\Request
     */
    public static function getRequest()
    {

        $request = \Symfony\Component\HttpFoundation\Request::createFromGlobals();

        $isJsonRequest = \SymfonyTools\Request\JsonRequestTransformer::isJsonRequest($request);

        if ($isJsonRequest === false) {
            return $request;
        }

        $transform = \SymfonyTools\Request\JsonRequestTransformer::transformJsonBody($request);

        if ($transform === false) {
            \SymfonyTools\Services\Exception::error('Unable to parse request.', 400);
        }

        return $request;

    }

}