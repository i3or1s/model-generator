<?php
declare(strict_types=1);

namespace i3or1sTest;

use i3or1s\ModelGenerator;
use PHPUnit\Framework\TestCase;

class ModelGeneratorTest extends TestCase
{
    public function testGeneratedModelShouldBeEmpty(): void {
        $this->assertEquals(ModelGenerator::class, ModelGenerator::createEmpty()::class);
    }

    public function testGeneratedModelShouldNotBeEmpty(): void {
        $generatedModel = ModelGenerator::createWithLibTemplates(__DIR__.'/templates/model');
        $this->assertEquals(ModelGenerator::class, $generatedModel::class);
        $this->expectException(\RuntimeException::class);
        $generatedModel->registerModel('simpleModel', '{}');
    }

    public function testRegisterAlreadyRegisteredModel(): void {
        $generatedModel = ModelGenerator::createWithLibTemplates(__DIR__.'/templates/model');
        $this->expectException(\RuntimeException::class);
        $generatedModel->registerModel('simpleModel', '{}');
    }

    public function testRegisterModelWithBadTemplate(): void {
        $generatedModel = ModelGenerator::createWithLibTemplates(__DIR__.'/templates/model');
        $this->expectException(\RuntimeException::class);
        $generatedModel->registerModel('simpleModelNew', '');
    }

    public function testGenerateModel(): void {
        $generatedModel = ModelGenerator::createWithLibTemplates(__DIR__.'/templates/model');
        $model = $generatedModel->generateModel('simpleModel', 6);
        $this->assertEquals(6, count($model));
    }

    public function testComplexModelStructure(): void {
        $expectedModel = [
            [
                "id" => "1",
                "complex" => [
                    ["type" => 1],
                    ["type" => 1]
                ]
            ]
        ];
        $generatedModel = ModelGenerator::createWithLibTemplates(__DIR__.'/templates/model');
        $model = $generatedModel->generateModel('complexModel');
        $this->assertEquals($expectedModel, $model);
    }
}