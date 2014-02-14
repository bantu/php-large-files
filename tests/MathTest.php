<?php

/*
* (c) Andreas Fischer <bantu@owncloud.com>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/

/**
* Tests math related to large file sizes.
*/
class MathTest extends PHPUnit_Framework_TestCase
{
    public function testFloatEqualityComparison()
    {
        $this->assertSame(4503599627370496, pow(2, 52));
    }

    public function testNumberCasting()
    {
        $this->assertSame(4503599627370496, 0 + '4503599627370496');
    }

    public function testLargeFloatStringWithoutWorkaround()
    {
        $this->assertSame('4503599627370496', (string) pow(2, 52));
    }

    public function testLargeFloatStringWithWorkaround()
    {
        $this->assertSame('4503599627370496', format_bytes(pow(2, 52)));
    }
}
