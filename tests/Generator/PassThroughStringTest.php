<?php
declare(strict_types=1);

namespace i3or1sTest\Generator;

use i3or1s\Generator\PassThroughString;
use PHPUnit\Framework\TestCase;

class PassThroughStringTest extends TestCase
{
    public function testPassedStringIsTheSame(): void {
        $string = 'same';
        $this->assertEquals($string, PassThroughString::pass($string));
    }
}