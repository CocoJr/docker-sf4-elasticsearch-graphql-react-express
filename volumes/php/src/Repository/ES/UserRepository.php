<?php
/**
 * @copyright 2018 Thibault Colette
 * @author Thibault Colette <thibaultcolette06@hotmail.fr>
 */

namespace App\Repository\ES;

use App\Entity\User;
use Elastica\Query;
use FOS\ElasticaBundle\Repository;
use Symfony\Bridge\Doctrine\Security\User\UserLoaderInterface;

class UserRepository extends Repository implements UserLoaderInterface
{
    /**
     * @param string $username
     *
     * @return User|null
     */
    public function loadUserByUsername($username)
    {
        if (!$username) {
            return null;
        }

        $query = [
            'query' => [
                'bool' => [
                    'must' => [
                        [
                            'match_phrase' => [
                                "username" => $username,
                            ]
                        ],
                    ],
                ]
            ],
        ];

        $users = $this->find($query);

        if (count($users) === 1) {
            return $users[0];
        }

        return null;
    }

    public function paginateForAdminList($page, $limit, $orderBy, $orderDir, $searches)
    {
        $query = [
            'sort' => [
                $orderBy => $orderDir,
            ],
        ];

        if ($searches) {
            foreach ($searches as $column => $search) {
                $search = '*'.str_replace(' ', '* *', $search).'*';
                if (is_numeric($column)) {
                    $query['query'] = ['query_string' => [
                        'query' => $search,
                        'fields' => ['username', 'email'],
                    ]];
                }
            }
        }

        $paginator = parent::findPaginated($query);

        $paginator->setMaxPerPage($limit);
        $paginator->setCurrentPage($page);

        return $paginator;
    }

    public function findById($id)
    {
        $query = [
            'query' => [
                'bool' => [
                    'must' => [
                        [
                            'match_phrase' => [
                                "_id" => $id,
                            ]
                        ],
                    ],
                ]
            ],
        ];

        $users = $this->find($query);

        if (count($users) === 1) {
            return $users[0];
        }

        return null;
    }
}
