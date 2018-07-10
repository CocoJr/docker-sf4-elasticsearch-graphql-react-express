<?php
/**
 * @author Thibault Colette <thibaultcolette06@hotmail.fr>
 * @copyright 2018 Thibault Colette
 */

namespace App\Entity\GraphQL\Payload;

use Overblog\GraphQLBundle\Annotation as GQL;

/**
 * Class AuthTokenPayload
 *
 * @GQL\GraphQLType(type="relay-mutation-payload")
 */
class AuthTokenPayload
{
    /**
     * @GQL\GraphQLToOne(target="AuthToken", nullable=true)
     */
    public $token;

    /**
     * @GQL\GraphQLToMany(target="FormError", nullable=true)
     */
    public $errors;
}
