<?php

if (! function_exists('auto_translate')) {
    function auto_translate(?string $text, string $to = null): string
    {
        if (empty(trim($text ?? ''))) {
            return $text ?? '';
        }

        $locale = $to ?? app()->getLocale();

        if ($locale === 'en') {
            return $text;
        }

        $cacheKey = 'trans_' . md5($text . '_' . $locale);

        return cache()->remember($cacheKey, now()->addDays(7), function () use ($text, $locale) {
            try {
                return \Stichoza\GoogleTranslate\GoogleTranslate::trans($text, $locale, 'en');
            } catch (\Throwable $e) {
                return $text;
            }
        });
    }
}
