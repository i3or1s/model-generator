<?php
declare(strict_types=1);

namespace i3or1s\Modifier;

use JetBrains\PhpStorm\Pure;

class AnyLatin2LatinASCII
{
    #[Pure] static public function transform(string $string): string {
        $response = transliterator_transliterate('Any-Latin; Latin-ASCII', $string);
        if(false === $response) {
            $response = $string;
        }
        return $response;
    }
}