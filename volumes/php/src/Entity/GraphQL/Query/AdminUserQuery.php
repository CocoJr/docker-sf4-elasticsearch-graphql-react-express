<?php
/**
 * @author Thibault Colette <thibaultcolette06@hotmail.fr>
 * @copyright 2018 Thibault Colette
 */

namespace App\Entity\GraphQL\Query;

use Overblog\GraphQLBundle\Annotation as GQL;

/**
 * Trait AdminUserQuery
 */
trait AdminUserQuery
{
    /**
     * @GQL\GraphQLQuery(
     *     type="UserPaginator",
     *     method="App\\GraphQL\\Resolver\\AdminUserResolver::getAll",
     *     input={
     *         "serv('fos_elastica.manager.orm')",
     *         {
     *             "name": "page",
     *             "target": "args['page']",
     *             "type": "Int",
     *             "description": "The page to fetch"
     *         },
     *         {
     *             "name": "limit",
     *             "target": "args['limit']",
     *             "type": "Int",
     *             "description": "The limit to fetch"
     *         },
     *         {
     *             "name": "orderBy",
     *             "target": "args['orderBy']",
     *             "type": "String",
     *             "description": "The order by clause"
     *         },
     *         {
     *             "name": "orderDir",
     *             "target": "args['orderDir']",
     *             "type": "String",
     *             "description": "The order by direction clause"
     *         },
     *         {
     *             "name": "searches",
     *             "target": "args['searches']",
     *             "type": "array",
     *             "description": "The searches terms"
     *         },
     *     }
     * )
     *
     * @GQL\GraphQLAccessControl(method="hasRole('ROLE_ADMIN')")
     */
    protected $adminUsers;

    /**
     * @GQL\GraphQLQuery(
     *     type="User",
     *     method="App\\GraphQL\\Resolver\\AdminUserResolver::get",
     *     input={
     *         "args['id']",
     *     }
     * )
     */
    protected $adminUser;
}