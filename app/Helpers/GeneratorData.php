<?php

if (!function_exists('generateRandomString')) {
    /**
     * Generate a random string with the specified length.
     *
     * @param int $length
     * @return string
     */
    function generateRandomString($length = 10) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }
}

if (!function_exists('generateArrayStringNumber')) {
    /**
     * Generate a array string from number.
     *
     * @param int $int1
     * @param int $int2
     * @return array
     */
    function generateArrayStringNumber($int1, $int2, $format = '%d') {
        $numbers = range($int1, $int2);
        $stringArray = array_map(function($number) use ($format) {
            return sprintf($format, $number);
        }, $numbers);

        return $stringArray;
    }
}
