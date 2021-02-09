<?php
declare(strict_types=1);

namespace i3or1sTest\Model;

use i3or1s\Model\AttributeGenerator;
use i3or1s\Model\ModelGenerator;
use PHPUnit\Framework\TestCase;

class ModelGeneratorTest extends TestCase
{
    protected static AttributeGenerator $autoIncAttributeGenerator;
    protected static AttributeGenerator $usernameAttributeGenerator;

    public static function setUpBeforeClass(): void {
        self::$autoIncAttributeGenerator = new AttributeGenerator(
            'test',
            'i3or1s\Generator\AutoInc::current',
            [],
            '%s',
            []
        );
        self::$usernameAttributeGenerator = new AttributeGenerator(
            'username',
            'i3or1s\Generator\PersonDictionary::firstName',
            [
                "gender" => "m",
                "country" => "sr",
                "order" => 0
            ],
            '%s@example.test',
            [
                'i3or1s\Modifier\AnyLatin2LatinASCII::transform',
                'strtolower'
            ]
        );
    }

    public function testModelIsCorrect(): void {
        $expectedArray = [
            self::$autoIncAttributeGenerator->attributeName() => self::$autoIncAttributeGenerator->transform(self::$autoIncAttributeGenerator->attributeValue()),
            self::$usernameAttributeGenerator->attributeName() => self::$usernameAttributeGenerator->transform(self::$usernameAttributeGenerator->attributeValue()),
        ];
        $model = ModelGenerator::generateModel([self::$autoIncAttributeGenerator, self::$usernameAttributeGenerator]);
        $this->assertArrayHasKey('test', $model);
        $this->assertArrayHasKey('username', $model);
        $this->assertEquals($expectedArray, $model);

    }
}