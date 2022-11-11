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
     * Encodes an array to a JSON string.
     *
     * @param array $array The json string being encoded
     *
     * @return string The json encoded string or an empty array in case of an error
     */
    public function arrayToJson(array $array): string
    {
        try {
            return json_encode($array, JSON_THROW_ON_ERROR);
        } catch (JsonException) {
            return '[]';
        }
    }

    /**
     * Encodes an array to a JSON string with pretty print.
     *
     * @param array $array The array to be encoded
     *
     * @return string The json encoded string or an empty array in case of an error
     */
    public function arrayToPrettyJson(array $array): string
    {
        try {
            return json_encode($array, JSON_THROW_ON_ERROR | JSON_PRETTY_PRINT);
        } catch (JsonException) {
            return '[]';
        }
    }
}
