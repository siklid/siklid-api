<?php

declare(strict_types=1);

namespace App\Foundation\Util;

use App\Foundation\Validation\ValidatorInterface;

/**
 * This class is used to wrap all request utilities.
 * This makes it easier to add new utilities without having to change the constructor of the Request class.
 */
final class RequestUtil
{
    private Json $json;

    private ValidatorInterface $validator;

    public function __construct(Json $json, ValidatorInterface $validator)
    {
        $this->json = $json;
        $this->validator = $validator;
    }

    public function json(): Json
    {
        return $this->json;
    }

    public function validator(): ValidatorInterface
    {
        return $this->validator;
    }
}
