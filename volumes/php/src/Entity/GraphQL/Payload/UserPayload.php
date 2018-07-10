<?php
/**
 * @author Thibault Colette <thibaultcolette06@hotmail.fr>
 * @copyright 2018 Thibault Colette
 */

namespace App\Entity\GraphQL\Payload;

use Overblog\GraphQLBundle\Annotation as GQL;

/**
 * Class UserPayload
 *
 * @GQL\GraphQLType(type="relay-mutation-payload")
 */
class UserPayload
{
    /**
     * @GQL\GraphQLToOne(target="User", nullable=true)
     */
    public $user;

    /**
     * @GQL\GraphQLToMany(target="FormError")
     */
    public $errors;
}
