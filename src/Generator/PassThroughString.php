<?php
declare(strict_types=1);

namespace i3or1s\Generator;

class PassThroughString
{
    static public function pass(string $param): string {
        return $param;
    }
}