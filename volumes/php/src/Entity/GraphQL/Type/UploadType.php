<?php
/**
 * @copyright 2018 Thibault Colette
 * @author Thibault Colette <thibaultcolette06@hotmail.fr>
 */

namespace App\Entity\GraphQL\Type;

use Overblog\GraphQLBundle\Annotation as GQL;

/**
 * Class UploadType
 *
 * @GQL\GraphQLAlias(name="Upload")
 * @GQL\GraphQLScalarType(type="@=newObject('Overblog\\GraphQLBundle\\Upload\\Type\\GraphQLUploadType')")
 */
class UploadType
{
}
