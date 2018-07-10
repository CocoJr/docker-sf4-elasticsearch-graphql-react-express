<?php
/**
 * @author Thibault Colette <thibaultcolette06@hotmail.fr>
 * @copyright 2018 Thibault Colette
 */

namespace App\GraphQL\Resolver;

use App\Entity\User;
use FOS\ElasticaBundle\Manager\RepositoryManagerInterface;
use Overblog\GraphQLBundle\Error\UserError;
use Pagerfanta\Exception\NotValidMaxPerPageException;

class AdminUserResolver extends BaseGraphQLResolver
{
    /**
     * @param RepositoryManagerInterface $repositoryManager
     * @param int $page
     * @param int $limit
     *
     * @return array
     */
    public function getAll(RepositoryManagerInterface $repositoryManager, $page, $limit, $orderBy, $orderDir, $searches): array
    {
        try {
            if ($limit > 100) {
                throw new NotValidMaxPerPageException();
            }

            $paginator = $repositoryManager->getRepository('App:User')
                ->paginateForAdminList($page, $limit, $orderBy, $orderDir, $searches);
        } catch (\Exception $exception) {
            throw new UserError($exception->getMessage(), $exception->getCode(), $exception);
        }

        $items = [];
        foreach ($paginator->getIterator() as $item) {
            $items[] = $item;
        }

        return [
            'page' => $page,
            'limit' => $limit,
            'total' => $paginator->getNbResults(),
            'items' => $items,
        ];
    }

    /**
     * @param RepositoryManagerInterface $repositoryManager
     * @param int $id
     *
     * @return User|null
     */
    public function get(RepositoryManagerInterface $repositoryManager, int $id): ?User
    {
        $user = $repositoryManager
            ->getRepository('App:User')
            ->findById($id);

        return $user;
    }
}