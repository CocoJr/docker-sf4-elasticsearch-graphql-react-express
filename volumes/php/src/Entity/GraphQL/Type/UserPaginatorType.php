<?php
/**
 * @copyright 2018 Thibault Colette
 * @author Thibault Colette <thibaultcolette06@hotmail.fr>
 */

namespace App\Entity\GraphQL\Type;

use Overblog\GraphQLBundle\Annotation as GQL;

/**
 * Class FormErrorType
 *
 * @GQL\GraphQLAlias(name="UserPaginator")
 */
class UserPaginatorType extends PaginatorType
{
    /**
     * @GQL\GraphQLColumn(type="[User]")
     */
    public $items;
}
