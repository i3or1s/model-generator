<?php
declare(strict_types=1);

namespace i3or1sTest\Model;

use i3or1s\Generator\AutoInc;
use i3or1s\Model\AttributeGenerator;
use PHPUnit\Framework\TestCase;

class AttributeGeneratorTest extends TestCase
{
    protected static AttributeGenerator $autoIncAttributeGenerator;

    public static function setUpBeforeClass(): void {
        self::$autoIncAttributeGenerator = new AttributeGenerator(
            'test',
            'i3or1s\Generator\AutoInc::current',
            [],
            '%s-UPPER',
            ['strtolower']
        );
    }

    public function testAttributeNameIsUnchanged(): void {
        $this->assertEquals('test', self::$autoIncAttributeGenerator->attributeName());
    }

    public function testAttributeValueIsPure(): void {
        $this->assertEquals(AutoInc::next(), self::$autoIncAttributeGenerator->attributeValue());
    }

    public function testTemplateIsProduced(): void {
        $id = self::$autoIncAttributeGenerator->attributeValue();
        $this->assertEquals(sprintf('%s-upper', $id), self::$autoIncAttributeGenerator->transform($id));
    }

    public function testInvalidGenerator(): void {
        $this->expectException(\RuntimeException::class);
        new AttributeGenerator(
            'test',
            'unknown', /** @phpstan-ignore-line */
            [],
            '%s-UPPER',
            ['strtolower']
        );
    }

    public function testInvalidParametersGenerator(): void {
        $this->expectException(\RuntimeException::class);
        new AttributeGenerator(
            'test',
            'mt_rand',
            [],
            '%s-UPPER',
            ['strtolower']
        );
    }

    public function testInvalidModifiers(): void {
        $this->expectException(\RuntimeException::class);
        new AttributeGenerator(
            'test',
            'uniqid',
            [],
            '%s-UPPER', /** @phpstan-ignore-next-line  */
            ['unknown']
        );
    }
}