<?php
declare(strict_types=1);

namespace i3or1sTest\Generator;

use i3or1s\Generator\FreeEmailDictionary;
use PHPUnit\Framework\TestCase;

class FreeEmailDictionaryTest extends TestCase
{
    public function testEmailShouldBeTheSame(): void {
        $this->assertEquals(FreeEmailDictionary::emailDomain(2), FreeEmailDictionary::emailDomain(2));
        $this->assertEquals(FreeEmailDictionary::emailDomain(3), FreeEmailDictionary::emailDomain(3));
    }

    public function testEmailDomainShouldBeValid(): void {
        $emailDomain = FreeEmailDictionary::emailDomain(FreeEmailDictionary::RANDOM);
        $this->assertEquals(true, false !== filter_var($emailDomain, FILTER_VALIDATE_DOMAIN), sprintf("Failed asserting that [%s] is a valid domain!", $emailDomain));
    }

    public function testEmailShouldBeRandom(): void {
        $this->assertNotEquals(FreeEmailDictionary::emailDomain(FreeEmailDictionary::RANDOM), FreeEmailDictionary::emailDomain(FreeEmailDictionary::RANDOM));
    }
}