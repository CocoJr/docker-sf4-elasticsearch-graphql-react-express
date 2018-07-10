<?php
/**
 * @copyright 2018 Thibault Colette
 * @author Thibault Colette <thibaultcolette06@hotmail.fr>
 */

namespace App\Tests\GraphQL\Resolver;

use App\DataFixtures\UserFixtures;
use App\GraphQL\Resolver\UserResolver;
use App\Tests\WebTestCase;

class UserResolverTest extends WebTestCase
{
    /**
     * @dataProvider providerGetLogged
     */
    public function testGetLogged($username)
    {
        $userResolver = new UserResolver();
        $payload = $userResolver->getLogged($this->getToken($username));

        if ($username) {
            $this->assertUser($payload);
        } else {
            $this->assertNull($payload);
        }
    }

    /**
     * @return array
     */
    public function providerGetLogged()
    {
        return [
            [
                null,
            ],
            [
                UserFixtures::ADMIN_BASE_USERNAME,
            ],
            [
                UserFixtures::USER_BASE_USERNAME,
            ],
        ];
    }

    /**
     * @dataProvider providerLogout
     */
    public function testLogout($username, $isSuccess)
    {
        $userResolver = new UserResolver();
        $payload = $userResolver->logout($this->getToken($username), $this->getContainer()->get('doctrine.orm.default_entity_manager'));

        if ($isSuccess) {
            $this->assertNull($payload);
        }
    }

    /**
     * @return array
     */
    public function providerLogout()
    {
        return [
            [
                null,
                true,
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
}