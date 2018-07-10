<?php
/**
 * @copyright 2018 Thibault Colette
 * @author Thibault Colette <thibaultcolette06@hotmail.fr>
 */

namespace App\Service;

use Symfony\Component\HttpKernel\Event\GetResponseEvent;

/**
 * Class RequestLanguage
 */
class RequestLanguage
{
    /**
     * @param GetResponseEvent $responseEvent
     */
    public function onKernelRequest(GetResponseEvent $responseEvent)
    {
        if (!$responseEvent->isMasterRequest()) {
            return;
        }

        $request = $responseEvent->getRequest();
        $locale = substr($request->headers->get('X-Locale', 'en'), 0, 2);

        if (!in_array($locale, self::getAllowedLocales())) {
            $locale = self::getAllowedLocales()[0];
        }

        $request->setLocale($locale);
    }

    public static function getAllowedLocales()
    {
        return [
            'fr',
            'en'
        ];
    }
}
