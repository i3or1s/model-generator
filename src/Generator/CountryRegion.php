<?php
declare(strict_types=1);

namespace i3or1s\Generator;

class CountryRegion
{
    const RANDOM = -1;

    /** @var array<string, int> */
    private static array $fileSize = [];

    private static int $prevKey = 0;

    public static function countryName(int $order = self::RANDOM): string {
        list($country, $shortCode) = explode('|', self::readLine(__DIR__.'/../../templates/country/en_country.txt', $order));

        return $country;
    }

    public static function countryShortCode(int $order = self::RANDOM): string {
        list($country, $shortCode) = explode('|', self::readLine(__DIR__.'/../../templates/country/en_country.txt', $order));

        return $shortCode;
    }

    public static function regionName(int $order = self::RANDOM): string {
        list($country, $shortCode) = explode('|', self::readLine(__DIR__.'/../../templates/country/en_country.txt', $order));
        $regionFile = sprintf('%s/../../templates/country/regions/en_%s_region.txt', __DIR__, strtolower($shortCode));
        list($region, $regionShortCode) = explode('|', self::readLine($regionFile, $order));

        return $region;
    }

    public static function regionShortCode(int $order = self::RANDOM): string {
        list($country, $shortCode) = explode('|', self::readLine(__DIR__.'/../../templates/country/en_country.txt', $order));
        $regionFile = sprintf('%s/../../templates/country/regions/en_%s_region.txt', __DIR__, strtolower($shortCode));
        list($region, $regionShortCode) = explode('|', self::readLine($regionFile, $order));

        return $regionShortCode??'';
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