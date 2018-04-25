<?php

namespace SymfonyTools\Response;

/**
 * @author ddniel16
 */
class JsonExceptionResponse
{

    /**
     * @param \Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent $event
     */
    public function onKernelException(
        \Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent $event
    )
    {

        $event->allowCustomResponseCode();

        $data = array();

        $exception = $event->getException();
        $msg = trim($exception->getMessage());

        if (! empty($msg)) {
            $data['error'] = array(
                'code' => $exception->getCode(),
                'message' => $exception->getMessage()
            );
        }

        $response = new \Symfony\Component\HttpFoundation\JsonResponse($data);
        if ($exception->getCode() !== 0) {
            $response->setStatusCode($exception->getCode());
        }

        $event->setResponse($response);

    }

}
