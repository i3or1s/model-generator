<?php
declare(strict_types=1);

namespace i3or1s\Generator;

class Phone
{
    static public function generate(): string {
        return sprintf("+%s-%s-%s-%s", mt_rand(0,100), mt_rand(60,69), mt_rand(100,999), mt_rand(1000, 9999));
    }
}