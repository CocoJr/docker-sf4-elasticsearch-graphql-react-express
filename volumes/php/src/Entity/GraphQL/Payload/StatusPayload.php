<?php
/**
 * @author Thibault Colette <thibaultcolette06@hotmail.fr>
 * @copyright 2018 Thibault Colette
 */

namespace App\Entity\GraphQL\Payload;

use Overblog\GraphQLBundle\Annotation as GQL;

/**
 * Class StatusPayload
 *
 * @GQL\GraphQLType(type="relay-mutation-payload")
 */
class StatusPayload
{
    /**
     * @GQL\GraphQLColumn(type="String")
     */
    public $status;
}
