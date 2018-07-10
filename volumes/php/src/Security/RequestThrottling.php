<?php
/**
 * @copyright 2018 Thibault Colette
 * @author Thibault Colette <thibaultcolette06@hotmail.fr>
 */

namespace App\Security;

use Predis\Client;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\Exception\TooManyRequestsHttpException;

/**
 * Class RequestThrottling
 */
class RequestThrottling
{
    /** @var int The seconds during the time we checking for the throttling */
    const DEFAULT_REQUEST_TTL = 10;
    /** @var int The maximum number of requests before throwing TooManyRequestsHttpException */
    const MAX_REQUEST = 100;

    /** @var Client */
    protected $redisClient;

    /**
     * RequestThrottling constructor.
     *
     * @param Client $redisClient
     */
    public function __construct(Client $redisClient)
    {
        $this->redisClient = $redisClient;
    }

    /**
     * @param GetResponseEvent $responseEvent
     *
     * @throws TooManyRequestsHttpException
     */
    public function onKernelRequest(GetResponseEvent $responseEvent)
    {
        if (!$responseEvent->isMasterRequest()) {
            return;
        }

        $request = $responseEvent->getRequest();
        $key = $request->getClientIp();

        if (!($count = $this->redisClient->get($key)) || ($ttl = $this->redisClient->ttl($key)) <= 0) {
            $count = 0;
            $ttl = self::DEFAULT_REQUEST_TTL;
        }

        $this->redisClient->set($key, ++$count);
        $this->redisClient->expireat($key, strtotime("+" . $ttl . " seconds"));
        $this->redisClient->save();

        if ($count >= self::MAX_REQUEST) {
            throw new TooManyRequestsHttpException($ttl);
        }
    }
}