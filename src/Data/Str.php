<?php

/**
 * This file is part of the Zest Framework.
 *
 * @author   Muhammad Umer Farooq <lablnet01@gmail.com>
 * @author-profile https://www.facebook.com/Muhammadumerfarooq01/
 *
 * For the full copyright and license information, please view the LICENSE
 *  file that was distributed with this source code.
 *
 * @since 3.0.0
 *
 * @license MIT
 */

namespace Zest\Data;

use Zest\Contracts\Data\Str as StrContract;

class Str implements StrContract
{
    /**
     * Get the encoding.
     *
     * @param string $encoding Valid encoding.
     *
     * @since 3.0.0
     *
     * @return string
     */
    private static function encoding($encoding = null) :string
    {
        return $encoding ?: mb_internal_encoding();
    }

    /**
     * Reverse the string.
     *
     * @param string $str      String to be evaluated.
     * @param string $encoding Valid encoding.
     *
     * @since 3.0.0
     *
     * @return string
     */
    public static function reverse(string $str, $encoding = null) :string
    {
        $newStr = '';
        $dataArr = (array) $str;
        $dataArr[] = self::encoding($encoding);
        $length = self::count($str);
        $dataArr = [$str, $length, 1];

        while ($dataArr[1]--) {
            $newStr .= call_user_func_array('mb_substr', $dataArr);
        }

        return $newStr;
    }

    /**
     * Concat the strings.
     *
     * @param string $g   With concat.
     * @param string $str String to concat.
     *
     * @since 3.0.0
     *
     * @return bool
     */
    public static function concat($g, ...$str)
    {
        return implode($g, $str);
    }

    /**
     * Count the string.
     *
     * @param string $str      String to be counted.
     * @param string $encoding Valid encoding.
     *
     * @since 3.0.0
     *
     * @return int
     */
    public static function count(string $str, $encoding = null)
    {
        if (function_exists('mb_strlen')) {
            return mb_strlen($str, self::encoding($encoding));
        }

        //This approach produce wrong result when use any encoding Scheme like UTF-8
        $i = 1;
        $str = $str."\0";
        while ($str[$i] != "\0") {
            $i++;
        }

        return $i;
    }

    /**
     * Check if string has atleast one uppercase.
     *
     * @param string      $str      String to be checked.
     * @param string|null $encoding Valid encoding.
     *
     * @return bool
     */
    public static function hasUpperCase(string $str, string $encoding = null): bool
    {
        return !(mb_strtolower($str, self::encoding($encoding)) === $str);
    }

    /**
     * Check if string has atleast one lowercase.
     *
     * @param string      $str      String to be checked.
     * @param string|null $encoding Valid encoding.
     *
     * @return bool
     */
    public static function hasLowerCase(string $str, string $encoding = null): bool
    {
        return !(mb_strtoupper($str, self::encoding($encoding)) === $str);
    }

    /**
     * Change case of character to opposite case.
     *
     * @param string $str      String to be changed.
     * @param string $encoding Valid encoding.
     *
     * @return string
     */
    public static function ConvertCase(string $str, $encoding = null)
    {
        if (function_exists('mb_strtolower') && function_exists('mb_strtoupper')) {
            $characters = preg_split('/(?<!^)(?!$)/u', $str);
            foreach ($characters as $key => $character) {
                if (mb_strtolower($character, self::encoding($encoding)) !== $character) {
                    $character = mb_strtolower($character, self::encoding($encoding));
                } else {
                    $character = mb_strtoupper($character, self::encoding($encoding));
                }
                $characters[$key] = $character;
            }

            return implode('', $characters);
        }

        // returns original string when mbstring is not active.
        return $str;
    }

    /**
     * Check if the input string is valid base64.
     *
     * @param string $string String to be tested
     *
     * @return bool
     */
    public static function isBase64(string $string)
    {
        // Check if there are valid base64 characters
        if (preg_match('/^[a-zA-Z0-9\/\r\n+]*={0,2}$/', $string) === 0) {
            return false;
        }

        // Decode the string in strict mode and check the results
        $decoded = base64_decode($string, true);
        if ($decoded === false) {
            return false;
        }

        // Encode the string again
        if (base64_encode($decoded) != $string) {
            return false;
        }

        return true;
    }

    /**
     * Return part of a string.
     *
     * @param string   $str      input string to process
     * @param int      $start    Start position
     * @param int|null $length   Length
     * @param string   $encoding optional encoding to use
     *
     * @return bool|string
     */
    public static function substring(string $str, int $start, $length = null, string $encoding = 'UTF-8'): string
    {
        return mb_substr($str, $start, $length, $encoding);
    }

    /**
     * Strip whitespace (or other characters) from the beginning and end of a string.
     *
     * @param string $string
     *
     * @return string
     */
    public static function stripWhitespaces(string $string)
    {
        return trim($string);
    }

    /**
     * Repeats string $amount|1 times.
     *
     * @param string $string
     * @param int    $amount
     *
     * @return string
     */
    public static function repeat(string $string, int $amount = 1)
    {
        return str_repeat($string, $amount);
    }

    /**
     * extracts a section of a string.
     *
     * @param string   $string String to extract section from
     * @param int      $start  Start position
     * @param int|null $length Length of extraction
     *
     * @return bool|string
     */
    public static function slice($string, int $start, ?int $length = null)
    {
        if ($start < 0) {
            $start += strlen($string);
        }
        if ($length < 0) {
            $length += strlen($string);
        }

        if ($length !== null && $length < $start) {
            return false;
        }

        return self::substring($string, $start, $length ?: null);
    }

    /**
     * Randomly shuffles the given string.
     *
     * @param string $string
     *
     * @return string
     */
    public static function shuffle(string $string)
    {
        return str_shuffle($string);
    }
}
