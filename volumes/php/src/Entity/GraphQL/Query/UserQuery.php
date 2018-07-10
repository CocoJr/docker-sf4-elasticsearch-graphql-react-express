<?php
/**
 * @author Thibault Colette <thibaultcolette06@hotmail.fr>
 * @copyright 2018 Thibault Colette
 */

namespace App\Entity\GraphQL\Query;

use Overblog\GraphQLBundle\Annotation as GQL;

/**
 * Trait UserQuery
 */
trait UserQuery
{
    /**
     * @GQL\GraphQLQuery(
     *     type="User",
     *     method="App\\GraphQL\\Resolver\\UserResolver::getLogged",
     *     input={
     *         "serv('security.token_storage')",
     *     }
     * )
     */
    protected $userMe;

    /**
     * @GQL\GraphQLQuery(
     *     type="User",
     *     method="App\\GraphQL\\Resolver\\UserResolver::logout",
     *     input={
     *         "serv('security.token_storage')",
     *         "serv('doctrine.orm.default_entity_manager')",
     *     }
     * )
     */
    protected $userLogout;
}