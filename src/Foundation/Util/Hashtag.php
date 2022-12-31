<?php

declare(strict_types=1);

namespace App\Foundation\Util;

final class Hashtag
{
    public function extract(string $text): array
    {
        $hashtags = [];
        $pattern = '/(#[\p{L}\d_]+)/u';
        preg_match_all($pattern, $text, $matches);

        foreach ($matches[0] as $match) {
            $hashtags[] = $match;
        }

        return $hashtags;
    }
}
