<?php

if (!function_exists('initials')) {
    function initials($name)
    {
        $words = explode(' ', $name);
        $initials = '';
        foreach ($words as $word) {
            if (!empty(trim($word))) {
                $initials .= strtoupper(mb_substr($word, 0, 1));
            }
        }
        return mb_substr($initials, 0, 2);
    }
}
