<?php

declare(strict_types=1);

if (!function_exists('array_merge_assoc_recursive')) {
    /**
     * {@author Eric Chau <eriic.chau@gmail.com}
     */
    function array_merge_assoc_recursive(array $array1, array $array2): array
    {
        foreach ($array2 as $key => $value) {
            if (is_int($key) && !in_array($array2[$key], $array1, true)) {
                $array1[] = $array2[$key];
            } elseif (!array_key_exists($key, $array1)) {
                $array1[$key] = $value;
            } elseif (is_array($value) && is_array($array1[$key]) ) {
                $array1[$key] = array_merge_assoc_recursive($array1[$key], $value);
            } else {
                if (!in_array($value, $array1, true)) {
                    $array1[$key] = $value;
                }
            }
        }

        return $array1;
    }
}
