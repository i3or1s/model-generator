<?php
declare(strict_types=1);

namespace i3or1sTest\Generator;

use i3or1s\Generator\AutoInc;
use PHPUnit\Framework\TestCase;

class AutoIncTest extends TestCase
{
    public function testIncrementShouldBeSequential(): void {
        $this->assertEquals(AutoInc::current()+1, AutoInc::next());
        AutoInc::next();
        AutoInc::next();
        $this->assertEquals(AutoInc::current()+1, AutoInc::next());
    }

    public function testIncrementShouldFailSequential(): void {
        $this->assertEquals(AutoInc::current()+1, AutoInc::next());
        $this->assertNotEquals(AutoInc::current(), AutoInc::next());
    }
}