<?php
declare(strict_types=1);

namespace i3or1s\Generator;

final class AutoInc
{
    private static AutoInc $instance ;
    private function __construct(private int $start = 0){}

    private static function instance(): AutoInc {
        if(isset(self::$instance)) {
            return self::$instance;
        }
        return self::$instance = new AutoInc();
    }

    public static function next(): int {
        return ++self::instance()->start;
    }

    public static function current(): int {
        return self::instance()->start;
    }
}