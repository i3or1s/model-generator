<?php
declare(strict_types=1);

namespace i3or1sTest\Modifier;

use i3or1s\Modifier\AnyLatin2LatinASCII;
use PHPUnit\Framework\TestCase;

class AnyLatin2LatinASCIITest extends TestCase
{
    public function testShouldTransformLetters(): void {
        $this->assertEquals('CCDSZccdsz', AnyLatin2LatinASCII::transform('ČĆĐŠŽčćđšž'));
    }
}