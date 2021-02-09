<?php
declare(strict_types=1);

namespace i3or1sTest\Generator;

use i3or1s\Generator\PersonDictionary;
use PHPUnit\Framework\TestCase;

class PersonDictionaryTest extends TestCase
{
    public function testMaleFirstNameForSrDictionaryShouldBeTheSame(): void {
        $this->assertEquals(
            PersonDictionary::firstName('sr', PersonDictionary::GENDER_M, 2),
            PersonDictionary::firstName('sr', PersonDictionary::GENDER_M, 2)
        );
    }

    public function testFemaleFirstNameForSrDictionaryShouldBeTheSame(): void {
        $this->assertEquals(
            PersonDictionary::firstName('sr', PersonDictionary::GENDER_F, 2),
            PersonDictionary::firstName('sr', PersonDictionary::GENDER_F, 2)
        );
    }

    public function testLastNameForSrDictionaryShouldBeTheSame(): void {
        $this->assertEquals(
            PersonDictionary::lastName('sr', 2),
            PersonDictionary::lastName('sr', 2)
        );
    }

    public function testFullNameForSrDictionaryShouldMatchFirstNameAndLastName(): void {
        $firstName = PersonDictionary::firstName('sr', PersonDictionary::GENDER_M, 2);
        $lastName = PersonDictionary::lastName('sr', 2);

        $this->assertEquals(
            PersonDictionary::fullName('sr', PersonDictionary::GENDER_M, 2),
            sprintf("%s %s", $firstName, $lastName)
        );
    }
}