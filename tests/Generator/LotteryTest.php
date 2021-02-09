<?php
declare(strict_types=1);

namespace i3or1sTest\Generator;

use i3or1s\Generator\Lottery;
use PHPUnit\Framework\TestCase;

class LotteryTest extends TestCase
{
    const LOTTERY = ['First', 'Second', 'Third'];
    public function testLotteryDraw(): void {
        $this->assertContains(Lottery::draw(self::LOTTERY), self::LOTTERY);
    }

    public function testLotteryDrawRigged(): void {
        $this->assertEquals(Lottery::draw(['Rigged']), 'Rigged');
    }
}