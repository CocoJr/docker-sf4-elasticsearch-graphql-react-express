<?php
/**
 * @author Thibault Colette <thibaultcolette06@hotmail.fr>
 * @copyright 2018 Thibault Colette
 */

namespace App\Entity\GraphQL\Type;

use Overblog\GraphQLBundle\Annotation as GQL;

/**
 * Class FormErrorType
 *
 * @GQL\GraphQLAlias(name="FormError")
 */
class FormErrorType
{
    /**
     * @GQL\GraphQLColumn(type="string", nullable=true)
     */
    public $key;

    /**
     * @GQL\GraphQLColumn(type="string")
     */
    public $message;
}
