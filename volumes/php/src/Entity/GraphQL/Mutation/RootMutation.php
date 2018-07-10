<?php

namespace App\Entity\GraphQL\Mutation;

use Overblog\GraphQLBundle\Annotation as GQL;

/**
 * Class RootMutation
 *
 * @GQL\GraphQLType(type="object")
 */
class RootMutation
{
    use UserMutation;
    use AdminUserMutation;
}