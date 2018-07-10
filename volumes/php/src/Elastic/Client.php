<?php
/**
 * @copyright 2018 Thibault Colette
 * @author Thibault Colette <thibaultcolette06@hotmail.fr>
 */

namespace App\Elastic;

use FOS\ElasticaBundle\Elastica\Client as EsClient;

class Client extends EsClient
{
    public function getIndex($name)
    {
        $currentDateTime = new \DateTime();
        $currentDateTime->setTimezone(new \DateTimeZone(date_default_timezone_get()));
        $name = str_replace('__DYNAMIC_NAME__', getenv('TEST_TOKEN'), $name);

        return parent::getIndex($name);
    }
}