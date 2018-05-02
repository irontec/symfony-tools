<?php

namespace SymfonyTools\Request;

/**
 * @author ddniel16
 */
class JsonRequestTransformer
{

    /**
     * @param \Symfony\Component\HttpKernel\Event\GetResponseEvent $event
     */
    public function onKernelRequest(
        \Symfony\Component\HttpKernel\Event\GetResponseEvent $event
    )
    {

        $request = $event->getRequest();

        if ($this->isJsonRequest($request) === false) {
            return;
        }

        $content = $request->getContent();
        if (empty($content)) {
            return;
        }

        if ($this->transformJsonBody($request) === false) {
            $response = \Symfony\Component\HttpFoundation\Response::create(
                'Unable to parse request.',
                400
            );
            $event->setResponse($response);
        }

    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return boolean
     */
    public static function isJsonRequest(
        \Symfony\Component\HttpFoundation\Request $request
    )
    {
        return 'json' === $request->getContentType();
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return boolean
     */
    public static function transformJsonBody(
        \Symfony\Component\HttpFoundation\Request $request
    )
    {

        $content = $request->getContent();

        if (trim($content) === '') {
            $content = '{}';
        }

        $data = json_decode($content, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            return false;
        }

        if ($data === null) {
            return true;
        }

        $request->request->replace($data);

        return true;

    }

}