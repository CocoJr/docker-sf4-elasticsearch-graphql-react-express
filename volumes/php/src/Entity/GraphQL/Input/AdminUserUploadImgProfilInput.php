<?php
/**
 * @copyright 2018 Thibault Colette
 * @author Thibault Colette <thibaultcolette06@hotmail.fr>
 */

namespace App\Entity\GraphQL\Input;

use Overblog\GraphQLBundle\Annotation as GQL;

/**
 * Class AdminUserUploadImgProfilInput
 *
 * @GQL\GraphQLType(type="relay-mutation-input")
 */
class AdminUserUploadImgProfilInput
{
    /**
     * @GQL\GraphQLColumn(type="ID", nullable=false)
     */
    public $id;

    /**
     * @GQL\GraphQLColumn(type="Upload", nullable=false)
     */
    public $file;
}
