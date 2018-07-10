<?php
/**
 * @copyright 2018 Thibault Colette
 * @author Thibault Colette <thibaultcolette06@hotmail.fr>
 */

namespace App\Entity\GraphQL\Payload;

use Overblog\GraphQLBundle\Annotation as GQL;

/**
 * Class UploadFilePayload
 *
 * @GQL\GraphQLType(type="relay-mutation-payload")
 */
class UploadFilePayload
{
    /**
     * @GQL\GraphQLColumn(type="File", nullable=true)
     */
    public $file;

    /**
     * @GQL\GraphQLToMany(target="FormError")
     */
    public $errors;
}

