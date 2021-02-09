<?php
declare(strict_types=1);

namespace i3or1s\Generator;


class FreeEmailDictionary
{
    const RANDOM = -1;

    /** @var array<string, int> */
    private static array $fileSize = [];

    private static int $prevKey = 0;

    public static function emailDomain(int $order = self::RANDOM): string {
        return self::readLine(__DIR__.'/../../templates/email/free_email_provider_domains.txt', $order);
    }

    private static function readLine(string $fileName, int $order = 0): string {
        $file = new \SplFileObject($fileName, 'r');
        if(!isset(self::$fileSize[$fileName])) {
            $file->seek(PHP_INT_MAX);
            self::$fileSize[$fileName] = $file->key();
        }
        if($order > self::$fileSize[$fileName]) {
            $order = self::$fileSize[$fileName];
        }
        if($order < 0) {
            $order = mt_rand(0, self::$fileSize[$fileName]);
            if($order === self::$prevKey) {
                $order = $order === self::$fileSize[$fileName] ? $order-1 : $order+1;
            }
        }
        self::$prevKey = $order;
        $file->seek($order);

        /** @var string $name */
        $name = $file->current();

        return trim($name);
    }
}