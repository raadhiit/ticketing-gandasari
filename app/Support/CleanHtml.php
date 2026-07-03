<?php

namespace App\Support;

use HTMLPurifier;
use HTMLPurifier_Config;

class CleanHtml
{
    public static function clean(?string $html): string
    {
        $config = HTMLPurifier_Config::createDefault();

        $config->set('HTML.Allowed', implode(',', [
            'div',
            'br',
            'p',
            'strong',
            'b',
            'em',
            'i',
            'u',
            's',
            'del',
            'h1',
            'ul',
            'ol',
            'li',
            'blockquote',
            'pre',
            'code',
            'a[href|target|rel]',
        ]));

        $config->set('Attr.AllowedFrameTargets', ['_blank']);
        $config->set('HTML.Nofollow', true);
        $config->set('HTML.TargetBlank', true);

        $purifier = new HTMLPurifier($config);

        return $purifier->purify($html ?? '');
    }

    public static function plainText(?string $html): string
    {
        $text = html_entity_decode($html ?? '', ENT_QUOTES | ENT_HTML5, 'UTF-8');

        return trim(strip_tags($text));
    }
}