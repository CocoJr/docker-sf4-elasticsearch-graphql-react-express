<?php
/**
 * @copyright 2018 Thibault Colette
 * @author Thibault Colette <thibaultcolette06@hotmail.fr>
 */

namespace App\Tests\Service;

use App\Security\RequestThrottling;
use App\Tests\WebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\Exception\TooManyRequestsHttpException;
use Symfony\Component\HttpKernel\HttpKernelInterface;

class RequestThrottlingTest extends WebTestCase
{
    /**
     * @dataProvider providerOnKernelRequest
     *
     * @param string $locale
     * @param string $expected
     */
    public function testOnKernelRequest($start, $expected, $subRequest, $isValid)
    {
        $request = new Request();
        $request->server->add(array('REMOTE_ADDR' => '127.0.0.1'));
        $responseEvent = new GetResponseEvent($this->getContainer()->get('http_kernel'), $request, $subRequest ? HttpKernelInterface::SUB_REQUEST : HttpKernelInterface::MASTER_REQUEST);
        $key = $request->getClientIp();

        self::bootKernel();
        $container = self::$container;
        $redisClient = $container->get('snc_redis.cache_client');
        $redisClient->set($key, $start);
        $redisClient->expireat($key, strtotime("+" . RequestThrottling::DEFAULT_REQUEST_TTL . " seconds"));
        $redisClient->save();

        $requestThrottling = new RequestThrottling($redisClient);

        try {
            $requestThrottling->onKernelRequest($responseEvent);
            $this->assertTrue($isValid);
            $this->assertEquals($expected, $redisClient->get($key));
        } catch (\Exception $e) {
            $this->assertFalse($isValid);
            $this->assertEquals(TooManyRequestsHttpException::class, get_class($e));
        }
    }

    /**
     * @return array
     */
    public function providerOnKernelRequest()
    {
        return [
            [
                0,
                1,
                false,
                true,
            ],
            [
                1,
                2,
                false,
                true,
            ],
            [
                100,
                100,
                true,
                true,
            ],
            [
                100,
                101,
                false,
                false,
            ],
        ];
    }
}