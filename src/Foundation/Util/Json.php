<?php

declare(strict_types=1);

namespace App\Foundation\Util;

use JsonException;

final class Json
{
    /**
     * Decodes JSON string to array.
     *
     * @param string $json The json string being decoded
     *
     * @return array The json decoded to an associative array
     */
    public function jsonToArray(string $json): array
    {
        try {
            return (array)json_decode($json, true, 512, JSON_THROW_ON_ERROR);
        } catch (JsonException) {
            return [];
        }
    }

    /**
     * Encodes Array to JSON string.
     *
     * @param array $array The json string being encoded
     *
     * @return String The json encoded to an Json
     */
    public function arrayToJson(array $array): string
    {
        try {
            return (string)json_encode($array, JSON_THROW_ON_ERROR, 512);
        } catch (\Exception $exception) {
            return '';
        }
    }
}
