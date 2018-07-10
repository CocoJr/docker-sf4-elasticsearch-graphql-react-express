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
 * @GQL\GraphQLAlias(name="Paginator")
 */
abstract class PaginatorType
{
    /**
     * @GQL\GraphQLColumn(type="Int")
     */
    public $page;

    /**
     * @GQL\GraphQLColumn(type="Int")
     */
    public $limit;

    /**
     * @GQL\GraphQLColumn(type="Int")
     */
    public $total;

    /**
     * @GQL\GraphQLColumn(type="array")
     */
    public $items;
}
