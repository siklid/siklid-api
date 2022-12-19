<?php

declare(strict_types=1);

namespace App\Foundation\Util;

final class Hashtag
{
    public function extract(string $text): array
    {
        $hashtags = [];
        preg_match_all('/(^|\B)#(?![0-9_]+\b)([a-zA-Z0-9_]|\p{Arabic}){1,30}(\b|\r)/u', $text, $matches);

        foreach ($matches[0] as $match) {
            $hashtags[] = $match;
        }

        return $hashtags;
    }
}
