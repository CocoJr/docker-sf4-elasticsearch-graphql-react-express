<?php
/**
 * @copyright 2018 Thibault Colette
 * @author Thibault Colette <thibaultcolette06@hotmail.fr>
 */

namespace App\Tests\Service;

use App\DataFixtures\UserFixtures;
use App\Tests\WebTestCase;

class UserRepositoryTest extends WebTestCase
{
    /**
     * @dataProvider providerLoadUserByUsername
     *
     * @param string $username
     * @param string $isValid
     *
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function testLoadUserByUsername($username, $isValid)
    {
        $user = $this->em->getRepository('App:User')
            ->loadUserByUsername($username);

        if ($isValid) {
            $this->assertUser($user);
        } else {
            $this->assertNull($user);
        }
    }

    /**
     * @return array
     */
    public function providerLoadUserByUsername()
    {
        return [
            [
                UserFixtures::USER_BASE_USERNAME,
                true,
            ],
            [
                UserFixtures::ADMIN_BASE_USERNAME,
                true,
            ],
            [
                'unknow',
                false,
            ],
            [
                null,
                false,
            ],
        ];
    }
}