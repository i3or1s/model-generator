<?php
declare(strict_types=1);

namespace i3or1sTest\Generator;

use i3or1s\Generator\EmptyString;
use PHPUnit\Framework\TestCase;

class EmptyStringTest extends TestCase
{
    public function testShouldBeEmptyString(): void {
        $this->assertEquals('', EmptyString::generate());
        $this->assertNotEquals('a', EmptyString::generate());
    }
}