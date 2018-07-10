<?php
/**
 * @copyright 2018 Thibault Colette
 * @author Thibault Colette <thibaultcolette06@hotmail.fr>
 */

namespace App\Tests\Service;

use App\DataFixtures\AuthTokenFixtures;
use App\DataFixtures\UserFixtures;
use App\Security\AuthTokenAuthenticator;
use App\Security\AuthTokenUserProvider;
use App\Tests\WebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\HttpUtils;

class AuthTokenAuthenticatorTest extends WebTestCase
{
    const PROVIDER_KEY = 'main';

    /**
     * @dataProvider providerCreateToken
     *
     * @param string $authToken
     */
    public function testCreateToken($authToken)
    {
        $request = new Request();
        $request->headers->add(array('X-Auth-Token' => $authToken));

        $httpUtils = new HttpUtils();
        $authTokenAuthenticator = new AuthTokenAuthenticator($httpUtils, $this->getToken(), $this->em);
        $token = $authTokenAuthenticator->createToken($request, self::PROVIDER_KEY);

        if (!$authToken) {
            $this->assertNull($token);
        } else {
            $this->assertTrue(is_object($token));
            $this->assertEquals($authToken, $token->getCredentials());
            $this->assertEquals(self::PROVIDER_KEY, $token->getProviderKey());
        }
    }

    /**
     * @return array
     */
    public function providerCreateToken()
    {
        return [
            [
                null,
            ],
            [
                AuthTokenFixtures::ADMIN_AUTH_TOKEN,
            ],
            [
                AuthTokenFixtures::USER_AUTH_TOKEN,
            ],
        ];
    }

    /**
     * @dataProvider providerAuthenticateToken
     *
     * @param string $authToken
     * @param string $username
     * @param string $email
     * @param array $errorsCallback
     */
    public function testAuthenticateToken($authToken, $username, $email, $errorsCallback)
    {
        $request = new Request();
        $request->headers->add(array('X-Auth-Token' => $authToken));

        $httpUtils = new HttpUtils();
        $authTokenAuthenticator = new AuthTokenAuthenticator($httpUtils, $this->getToken(), $this->em);
        $token = $authTokenAuthenticator->createToken($request, self::PROVIDER_KEY);

        if ($authToken) {
            $authTokenProvider = new AuthTokenUserProvider($this->em, $this->getContainer()->get('fos_elastica.manager.orm'));
            try {
                $auth = $authTokenAuthenticator->authenticateToken($token, $authTokenProvider, self::PROVIDER_KEY);
                $this->assertTrue(is_object($auth));
                $this->assertEquals($authToken, $auth->getCredentials());
                $this->assertEquals(self::PROVIDER_KEY, $auth->getProviderKey());

                $this->assertUser($auth->getUser(), $username, $email);
            } catch(\Exception $e) {
                $this->assertNotEmpty($errorsCallback);
                foreach ($errorsCallback as $errorCallback) {
                    $this->$errorCallback($e->getMessage());
                }
            }
        } else {
            $this->assertNull($token);
        }
    }

    /**
     * @return array
     */
    public function providerAuthenticateToken()
    {
        return [
            [
                null,
                null,
                null,
                null,
            ],
            [
                AuthTokenFixtures::ADMIN_AUTH_TOKEN,
                UserFixtures::ADMIN_BASE_USERNAME,
                UserFixtures::ADMIN_BASE_USERNAME.'@example.fr',
                null,
            ],
            [
                AuthTokenFixtures::USER_AUTH_TOKEN,
                UserFixtures::USER_BASE_USERNAME,
                UserFixtures::USER_BASE_USERNAME.'@example.fr',
                null,
            ],
            [
                AuthTokenFixtures::ADMIN_AUTH_TOKEN_INVALID,
                UserFixtures::ADMIN_BASE_USERNAME,
                UserFixtures::ADMIN_BASE_USERNAME.'@example.fr',
                ['assertInvalidAuthToken'],
            ],
            [
                AuthTokenFixtures::USER_AUTH_TOKEN_INVALID,
                UserFixtures::USER_BASE_USERNAME,
                UserFixtures::USER_BASE_USERNAME.'@example.fr',
                ['assertInvalidAuthToken'],
            ],
            [
                AuthTokenFixtures::USER_AUTH_TOKEN_DISABLE,
                UserFixtures::USER_DISABLE_USERNAME,
                UserFixtures::USER_DISABLE_USERNAME.'@example.fr',
                ['assertInvalidAuthToken'],
            ],
        ];
    }

    public function testOnAuthenticationFailure()
    {
        $request = new Request();
        $authException = new AuthenticationException('auth.message');

        $httpUtils = new HttpUtils();
        $authTokenAuthenticator = new AuthTokenAuthenticator($httpUtils, $this->getToken(), $this->em);
        $authTokenAuthenticator->onAuthenticationFailure($request, $authException);
        $this->assertTrue(true);
    }

    protected function assertInvalidAuthToken($message)
    {
        $this->assertEquals('Invalid authentication token', $message);
    }
}