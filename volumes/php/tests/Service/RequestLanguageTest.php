<?php
/**
 * @copyright 2018 Thibault Colette
 * @author Thibault Colette <thibaultcolette06@hotmail.fr>
 */

namespace App\Tests\Service;

use App\Service\RequestLanguage;
use App\Tests\WebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\HttpKernelInterface;

class RequestLanguageTest extends WebTestCase
{
    /**
     * @dataProvider providerOnKernelRequest
     *
     * @param string $locale
     * @param string $expected
     */
    public function testOnKernelRequest($responseEvent, $expected)
    {
        $requestLanguage = new RequestLanguage();
        $requestLanguage->onKernelRequest($responseEvent);

        $request = $responseEvent->getRequest();
        $this->assertEquals($expected, $request->getLocale());
    }

    /**
     * @return array
     */
    public function providerOnKernelRequest()
    {
        $enRequest = new Request();
        $enRequest->headers->add(array('X-Locale' => 'en'));
        $frRequest = new Request();
        $frRequest->headers->add(array('X-Locale' => 'fr'));
        $sdRequest = new Request();
        $sdRequest->headers->add(array('X-Locale' => 'sd'));

        $enResponseEvent = new GetResponseEvent($this->getContainer()->get('http_kernel'), $enRequest, HttpKernelInterface::MASTER_REQUEST);
        $frResponseEvent = new GetResponseEvent($this->getContainer()->get('http_kernel'), $frRequest, HttpKernelInterface::MASTER_REQUEST);
        $sdResponseEvent = new GetResponseEvent($this->getContainer()->get('http_kernel'), $sdRequest, HttpKernelInterface::MASTER_REQUEST);
        $enSubResponseEvent = new GetResponseEvent($this->getContainer()->get('http_kernel'), $enRequest, HttpKernelInterface::SUB_REQUEST);
        $frSubResponseEvent = new GetResponseEvent($this->getContainer()->get('http_kernel'), $frRequest, HttpKernelInterface::SUB_REQUEST);

        return [
            [
                $enResponseEvent,
                'en',
            ],
            [
                $frResponseEvent,
                'fr',
            ],
            [
                $sdResponseEvent,
                'fr',
            ],
            [
                $enSubResponseEvent,
                'en',
            ],
            [
                $frSubResponseEvent,
                'fr',
            ],
        ];
    }
}