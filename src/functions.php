<?php

/*
* (c) Andreas Fischer <bantu@owncloud.com>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/

/**
* Formats a signed integer or float as an unsigned integer strings.
*
* @param int|float|string
*
* @return string Unsigned integer string
*/
function format_bytes($number)
{
    if (is_int($number)) {
        return sprintf('%u', $number);
    } else if (is_float($number)) {
        return number_format($number, 0, '', '');
    } else {
        return $number;
    }
}
