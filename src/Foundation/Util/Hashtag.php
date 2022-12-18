<?php

declare(strict_types=1);

namespace App\Foundation\Util;

/**
 * This class is used to extract hashtags from a text.
 * Current implementation is temporary and should be replaced with a more
 * robust solution in the context of this PR.
 *
 * @see {@link https://github.com/piscibus/siklid-api/pull/115}
 */
final class Hashtag
{
    public function extract(string $text): array
    {
        $hashtags = [];
        preg_match_all('/#(\S+)/', $text, $matches);
        foreach ($matches[0] as $match) {
            $hashtags[] = mb_strtolower($match);
        }

        return $hashtags;
    }
}
