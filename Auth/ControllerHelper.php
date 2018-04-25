<?php

namespace SymfonyTools\Auth;

/**
 * @author ddniel16
 */
trait ControllerHelper
{

    /**
     * Set base HTTP headers.
     *
     * @param \Symfony\Component\HttpFoundation\Response $response
     * @return \Symfony\Component\HttpFoundation\Response
     */
    private function setBaseHeaders(
        \Symfony\Component\HttpFoundation\Response $response
    )
    {

        $response->headers->set('Content-Type', 'application/json');
        $response->headers->set('Access-Control-Allow-Origin', '*');

        return $response;

    }

    /**
     * Data serializing via JMS serializer.
     *
     * @param mixed $data
     * @return string JSON string
     */
    public function serialize($data)
    {

        $context = new \JMS\Serializer\SerializationContext();
        $context->setSerializeNull(true);

        return $this->get('jms_serializer')->serialize(
            $data,
            'json',
            $context
        );

    }

    /**
     * @param \FOS\UserBundle\Model\User $user
     * @return string
     */
    public function getToken(\FOS\UserBundle\Model\User $user)
    {

        $encode = array(
            'username' => $user->getUsername(),
            'exp' => $this->getTokenExpiryDateTime()
        );

        $encoder = $this->container->get('lexik_jwt_authentication.encoder');
        return $encoder->encode($encode);

    }

    /**
     * Returns token expiration datetime.
     * @return string
     */
    private function getTokenExpiryDateTime()
    {

        $tokenTtl = $this->container->getParameter(
            'lexik_jwt_authentication.token_ttl'
        );

        $now = new \DateTime();
        $now->add(new \DateInterval('PT' . $tokenTtl . 'S'));

        return $now->format('U');

    }

}
