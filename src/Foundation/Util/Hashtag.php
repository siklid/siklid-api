<?php

declare(strict_types=1);

namespace App\Foundation\Util;

use function Symfony\Component\String\u;

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
        preg_match_all('/(^|\B)#(?![0-9_]+\b)([a-zA-Z0-9_]|\p{Arabic}){1,30}(\b|\r)/u', $text, $matches);
        foreach ($matches[0] as $match) {
            $hashtags[] = u(mb_strtolower($match))->toString();
        }

        return $hashtags;
    }
}
