<?php

namespace SymfonyTools\Auth;

use \Lexik\Bundle\JWTAuthenticationBundle\Encoder\JWTEncoderInterface;
use \Lexik\Bundle\JWTAuthenticationBundle\TokenExtractor\AuthorizationHeaderTokenExtractor;
use \Symfony\Component\HttpFoundation\Request;
use \Symfony\Component\HttpFoundation\Response;
use \Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use \Symfony\Component\Security\Core\Exception\AuthenticationException;
use \Symfony\Component\Security\Core\User\UserInterface;

/**
 * @author ddniel16
 */
class TokenAuthenticator
    extends \Symfony\Component\Security\Guard\AbstractGuardAuthenticator
{

    /**
     * @var \Lexik\Bundle\JWTAuthenticationBundle\Encoder\DefaultEncoder
     */
    protected $jwtEncoder;

    /**
     * @var \Doctrine\ORM\EntityManager
     */
    protected $em;

    protected $jwtTokenTTL;

    public function __construct(
        JWTEncoderInterface $jwtEncoder,
        \Doctrine\ORM\EntityManager $em,
        $jwtTokenTTL
    )
    {

        $this->jwtEncoder = $jwtEncoder;
        $this->em = $em;
        $this->jwtTokenTTL = $jwtTokenTTL;
    }

    /**
     * {@inheritDoc}
     * @see \Symfony\Component\Security\Guard\AuthenticatorInterface::getCredentials()
     */
    public function getCredentials(Request $request)
    {

        $extractor = new AuthorizationHeaderTokenExtractor(
            'Bearer',
            'Authorization'
        );

        $token = $extractor->extract($request);
        if ($token === false) {
            throw new \Exception('Not authorized', 401);
        }

        return $token;

    }

    /**
     * {@inheritDoc}
     * @see \Symfony\Component\Security\Guard\AuthenticatorInterface::getUser()
     */
    public function getUser(
        $credentials,
        \Symfony\Component\Security\Core\User\UserProviderInterface $userProvider
    )
    {

        $data = $this->jwtEncoder->decode($credentials);

        if ($data === false) {
            throw new \Exception(
                'Invalid credentials',
                Response::HTTP_FORBIDDEN
            );
        }

        $username = $data['username'];

        return $this->em->getRepository(\App\Entity\User::class)->findOneBy(
            ['username' => $username]
        );

    }

    /**
     * {@inheritDoc}
     * @see \Symfony\Component\Security\Guard\AuthenticatorInterface::checkCredentials()
     */
    public function checkCredentials(
        $credentials,
        UserInterface $user
    )
    {

        $decode = $this->jwtEncoder->decode($credentials);

        if ($decode['salt'] === $user->getPassword()) {
            return true;
        }

        return false;
    }

    /**
     * {@inheritDoc}
     * @see \Symfony\Component\Security\Guard\AuthenticatorInterface::onAuthenticationFailure()
     */
    public function onAuthenticationFailure(
        Request $request,
        AuthenticationException $exception
    )
    {
        throw new \Exception('Invalid credentials', Response::HTTP_FORBIDDEN);
    }

    /**
     * {@inheritDoc}
     * @see \Symfony\Component\Security\Guard\AuthenticatorInterface::onAuthenticationSuccess()
     */
    public function onAuthenticationSuccess(
        Request $request,
        TokenInterface $token,
        $providerKey
    )
    {
        return null;
    }

    /**
     * {@inheritDoc}
     * @see \Symfony\Component\Security\Guard\AuthenticatorInterface::supportsRememberMe()
     */
    public function supportsRememberMe()
    {
        return false;
    }

    public function start(
        Request $request,
        AuthenticationException $authException = null
    )
    {
        throw new \Exception(
            'Invalid credentials',
            Response::HTTP_FORBIDDEN
        );
    }

    /**
     * {@inheritDoc}
     * @see \Symfony\Component\Security\Guard\AuthenticatorInterface::supports()
     */
    public function supports(Request $request)
    {
        return true;
    }

    /**
     * Returns token expiration datetime.
     * @return string
     */
    public function getTokenExpiryDateTime()
    {

        $now = new \DateTime();
        $now->add(new \DateInterval('PT' . $this->jwtTokenTTL . 'S'));

        return $now->format('U');

    }

}
