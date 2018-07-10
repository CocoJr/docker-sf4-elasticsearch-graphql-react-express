<?php
/**
 * @author Thibault Colette <thibaultcolette06@hotmail.fr>
 * @copyright 2018 Thibault Colette
 */

namespace App\Security;

use App\Entity\AuthToken;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\PreAuthenticatedToken;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationFailureHandlerInterface;
use Symfony\Component\Security\Http\Authentication\SimplePreAuthenticatorInterface;
use Symfony\Component\Security\Http\HttpUtils;

class AuthTokenAuthenticator implements SimplePreAuthenticatorInterface, AuthenticationFailureHandlerInterface
{
    /**
     * Token validity: 12 hours
     */
    const TOKEN_VALIDITY_DURATION = 12 * 3600;

    protected $httpUtils;
    protected $tokenStorage;
    protected $entityManager;

    public function __construct(HttpUtils $httpUtils, TokenStorageInterface $tokenStorage, EntityManagerInterface $entityManager)
    {
        $this->httpUtils = $httpUtils;
        $this->tokenStorage = $tokenStorage;
        $this->entityManager = $entityManager;
    }

    public function createToken(Request $request, $providerKey)
    {
        $authTokenHeader = $request->headers->get('X-Auth-Token');
        if (!$authTokenHeader) {
            $authTokenHeader = $request->cookies->get('X-Auth-Token');
            if (!$authTokenHeader) {
                return;
            }
        }

        return new PreAuthenticatedToken(
            'anon.',
            $authTokenHeader,
            $providerKey
        );
    }

    public function authenticateToken(TokenInterface $token, UserProviderInterface $userProvider, $providerKey)
    {
        // @codeCoverageIgnoreStart
        if (!$userProvider instanceof AuthTokenUserProvider) {
            throw new \InvalidArgumentException(
                sprintf(
                    'The user provider must be an instance of AuthTokenUserProvider (%s was given).',
                    get_class($userProvider)
                )
            );
        }
        // @codeCoverageIgnoreEnd

        $authTokenHeader = $token->getCredentials();
        $authToken = $userProvider->getAuthToken($authTokenHeader);

        if (!$authToken || !$this->isTokenValid($authToken)) {
            throw new BadCredentialsException('Invalid authentication token');
        }

        $user = $authToken->getUser();
        $this->entityManager->refresh($user);

        if (!$user->isEnabled()) {
            throw new BadCredentialsException('Invalid authentication token');
        }

        $pre = new PreAuthenticatedToken(
            $user,
            $authTokenHeader,
            $providerKey,
            $user->getRoles()
        );

        $pre->setAuthenticated(true);

        return $pre;
    }

    /**
     * @param TokenInterface $token
     * @param string         $providerKey
     *
     * @return bool
     *
     * @codeCoverageIgnore
     */
    public function supportsToken(TokenInterface $token, $providerKey)
    {
        return $token instanceof PreAuthenticatedToken && $token->getProviderKey() === $providerKey;
    }

    /**
     * Vérifie la validité du token
     *
     * @param AuthToken $authToken
     *
     * @return bool
     */
    private function isTokenValid(AuthToken $authToken)
    {
        return (time() - $authToken->getCreatedAt()->getTimestamp()) < self::TOKEN_VALIDITY_DURATION;
    }

    /**
     * @param Request                 $request
     * @param AuthenticationException $exception
     *
     * @return JsonResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
    }
}