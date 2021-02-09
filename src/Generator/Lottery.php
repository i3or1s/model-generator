<?php
declare(strict_types=1);

namespace i3or1s\Generator;

class Lottery
{
    /**
     * @param array<mixed> $choices
     * @return string
     */
    static public function draw(array $choices): string {
        return $choices[mt_rand(0, count($choices)-1)];
    }
}