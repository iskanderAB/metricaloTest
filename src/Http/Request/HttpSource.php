<?php
declare(strict_types=1);

namespace App\Http\Request;

use Symfony\Component\HttpFoundation\Request;

abstract class HttpSource
{


    /**
     * @param Request $source
     * @param string $key
     * @return bool|float|int|string|null
     *
     */
    public static function fromBody(Request $source, string $key):  bool|float|int|null|string
    {
        return $source->getPayload()->get($key);
    }


    public static function fromAttributes(Request $request, string $key):  string
    {
        return $request->attributes->get($key);
    }


}
