<?php
declare(strict_types=1);

namespace i3or1s\Generator;

use JetBrains\PhpStorm\ExpectedValues;

class PersonDictionary
{
    CONST GENDER_M = 'm';
    CONST GENDER_F = 'f';
    const RANDOM = -1;

    /** @var array<string, int> */
    private static array $fileSize = [];

    public static function firstName(string $country = null, #[ExpectedValues(valuesFromClass: PersonDictionary::class)] string $gender = null, int $order = self::RANDOM): string {
        $iterator = new \DirectoryIterator(__DIR__.'/../../templates/names');
        $files = [];
        foreach ($iterator as $file) {
            if('file' === $file->getType()) {
                $baseName = $file->getBasename();
                $pattern = sprintf('#%s_%s_firstname#', $country ?? '.*?', $gender ?? '.*?');
                if(preg_match($pattern, $baseName)) {
                    $files[] = $file->getFileInfo();
                }
            }
        }
        $keyFile = array_rand($files, 1);


        $fileName = $files[$keyFile]->getRealPath();
        if(false === $fileName) {
            return 'Lorem';
        }

        return self::readLine($fileName, $order);
    }

    public static function lastName(?string $country = null, int $order = self::RANDOM): string {
        $iterator = new \DirectoryIterator(__DIR__.'/../../templates/names');
        $files = [];
        foreach ($iterator as $file) {
            if('file' === $file->getType()) {
                $baseName = $file->getBasename();
                $pattern = sprintf('#%s_lastname#', $country ?? '.*?');
                if(preg_match($pattern, $baseName)) {
                    $files[] = $file->getFileInfo();
                }
            }
        }
        $keyFile = array_rand($files, 1);


        $fileName = $files[$keyFile]->getRealPath();
        if(false === $fileName) {
            return 'Ipsum';
        }

        return self::readLine($fileName, $order);
    }

    public static function fullName(?string $country = null, #[ExpectedValues(valuesFromClass: PersonDictionary::class)] ?string $gender = null, int $order = self::RANDOM): string  {
        return sprintf('%s %s', self::firstName($country, $gender, $order), self::lastName($country, $order));
    }

    private static function readLine(string $fileName, int $order = 0): string {
        $file = new \SplFileObject($fileName, 'r');
        if(!isset(self::$fileSize[$fileName])) {
            $file->seek(PHP_INT_MAX);
            self::$fileSize[$fileName] = $file->key();
        }
        if($order < 0) {
            //$order = rand(0, self::$fileSize[$fileName]);
            $order = mt_rand(0, self::$fileSize[$fileName]);
        }
        $file->seek($order);

        if(empty($file->current()) || $file->current() === '') {
            $file->seek($file->key());
        }
        /** @var string $name */
        $name = $file->current();

        return trim($name);
    }
}
