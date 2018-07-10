<?php
/**
 * @author Thibault Colette <thibaultcolette06@hotmail.fr>
 * @copyright 2018 Thibault Colette
 */

namespace App\Entity\GraphQL\Query;

use Overblog\GraphQLBundle\Annotation as GQL;

/**
 * Class RootQuery
 *
 * @GQL\GraphQLType(type="object")
 */
class RootQuery
{
    use UserQuery;
    use AdminUserQuery;
}