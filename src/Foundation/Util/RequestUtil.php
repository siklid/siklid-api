<?php

declare(strict_types=1);

namespace App\Foundation\Util;

/**
 * This class is used to wrap all request utilities.
 * This makes it easier to add new utilities without having to change the constructor of the Request class.
 */
final class RequestUtil
{
    private Json $json;

    public function __construct(Json $json)
    {
        $this->json = $json;
    }

    public function json(): Json
    {
        return $this->json;
    }
}
