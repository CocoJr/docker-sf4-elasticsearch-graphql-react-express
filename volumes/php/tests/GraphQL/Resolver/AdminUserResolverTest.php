<?php
/**
 * @copyright 2018 Thibault Colette
 * @author Thibault Colette <thibaultcolette06@hotmail.fr>
 */

namespace App\Tests\GraphQL\Resolver;

use App\DataFixtures\UserFixtures;
use App\GraphQL\Resolver\AdminUserResolver;
use App\Tests\WebTestCase;
use GraphQL\Error\UserError;

class AdminUserResolverTest extends WebTestCase
{
    /**
     * @dataProvider providerGetAll
     */
    public function testGetAll($page, $limit, $orderBy, $orderDir, $isValid)
    {
        $userResolver = new AdminUserResolver();
        try {
            $payload = $userResolver->getAll($this->getContainer()->get('fos_elastica.manager.orm'), $page, $limit, $orderBy, $orderDir, null);

            $this->assertPagination($payload);
        } catch (UserError $e) {
            $this->assertFalse($isValid);
        }
    }

    /**
     * @return array
     */
    public function providerGetAll()
    {
        return [
            [
                1,
                10,
                'id',
                'desc',
                true,
            ],
            [
                2,
                10,
                'id',
                'desc',
                true,
            ],
            [
                1,
                100,
                'id',
                'desc',
                true,
            ],
            [
                1000,
                10,
                'id',
                'desc',
                false,
            ],
            [
                -1,
                10,
                'id',
                'desc',
                false,
            ],
            [
                1,
                10000,
                'id',
                'desc',
                false,
            ],
        ];
    }

    /**
     * @dataProvider providerGet
     */
    public function testGet($id, $isValid)
    {
        $userResolver = new AdminUserResolver();
        $payload = $userResolver->get($this->getContainer()->get('fos_elastica.manager.orm'), $id);

        if ($isValid) {
            $this->assertUser($payload);
        } else {
            $this->assertNull($payload);
        }
    }

    /**
     * @return array
     */
    public function providerGet()
    {
        return [
            [
                1,
                true,
            ],
            [
                2,
                true,
            ],
            [
                3,
                true,
            ],
            [
                -1,
                false,
            ],
            [
                UserFixtures::NB_USERS+1,
                false,
            ],
        ];
    }
}