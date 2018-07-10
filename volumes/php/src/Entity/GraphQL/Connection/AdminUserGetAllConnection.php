<?php
/**
 * @author Thibault Colette <thibaultcolette06@hotmail.fr>
 * @copyright 2018 Thibault Colette
 */

namespace App\Entity\GraphQL\Connection;

use Overblog\GraphQLBundle\Annotation as GQL;

/**
 * Class AdminUserGetAllConnection
 *
 * @GQL\GraphQLType(type="relay-connection")
 * @GQL\GraphQLNode(type="User")
 */
class AdminUserGetAllConnection
{
}
