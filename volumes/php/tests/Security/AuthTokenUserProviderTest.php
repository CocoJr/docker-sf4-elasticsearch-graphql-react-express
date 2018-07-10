<?php
/**
 * @copyright 2018 Thibault Colette
 * @author Thibault Colette <thibaultcolette06@hotmail.fr>
 */

namespace App\Tests\Service;

use App\DataFixtures\AuthTokenFixtures;
use App\DataFixtures\UserFixtures;
use App\Security\AuthTokenUserProvider;
use App\Tests\WebTestCase;
use Symfony\Component\Security\Core\User\User;

class AuthTokenUserProviderTest extends WebTestCase
{
    /**
     * @dataProvider providerGetAuthToken
     *
     * @param string $authToken
     *
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function testGetAuthToken($authToken)
    {
        $authTokenUserProvider = new AuthTokenUserProvider($this->em, $this->getContainer()->get('fos_elastica.manager.orm'));
        $token = $authTokenUserProvider->getAuthToken($authToken);

        if (!$authToken) {
            $this->assertNull($token);
        } else {
            $this->assertToken($token);
        }
    }

    /**
     * @return array
     */
    public function providerGetAuthToken()
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
     * @dataProvider providerLoadUserByUsername
     *
     * @param string $username
     * @param bool $isValid
     */
    public function testLoadUserByUsername($username, $isValid)
    {
        $authTokenUserProvider = new AuthTokenUserProvider($this->em, $this->getContainer()->get('fos_elastica.manager.orm'));
        $user = $authTokenUserProvider->loadUserByUsername($username);

        if (!$isValid) {
            $this->assertNull($user);
        } else {
            $this->assertUser($user);
        }
    }

    /**
     * @return array
     */
    public function providerLoadUserByUsername()
    {
        return [
            [
                null,
                false,
            ],
            [
                UserFixtures::ADMIN_BASE_USERNAME,
                true,
            ],
            [
                UserFixtures::USER_BASE_USERNAME,
                true,
            ],
        ];
    }

    /**
     * @dataProvider providerSupportsClass
     *
     * @param string $class
     * @param bool $expected
     */
    public function testSupportsClass($class, $expected)
    {
        $authTokenUserProvider = new AuthTokenUserProvider($this->em, $this->getContainer()->get('fos_elastica.manager.orm'));

        $this->assertEquals($expected, $authTokenUserProvider->supportsClass($class));
    }

    /**
     * @return array
     */
    public function providerSupportsClass()
    {
        return [
            [
                null,
                false,
            ],
            [
                UserFixtures::class,
                false,
            ],
            [
                User::class,
                true,
            ],
        ];
    }
}