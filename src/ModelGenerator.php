<?php
declare(strict_types=1);

namespace i3or1s;

use i3or1s\Generator\EmptyString;
use i3or1s\Generator\PassThroughString;
use i3or1s\Model\AttributeGenerator;

class ModelGenerator
{
    /** @var array<string, array> */
    private array $models = [];

    private function __construct()
    {
    }

    static public function createWithLibTemplates(string $libLocation = __DIR__.'/../templates/models'): self {
        $self = new self();
        if(!is_dir($libLocation)) {
            throw new \RuntimeException("Not a valid directory!");
        }
        $iterator = new \DirectoryIterator($libLocation);
        foreach($iterator as $file) {
            if($file->isDir() || $file->getExtension() != 'json') {
                continue;
            }
            $name = $file->getBasename('.json');
            /** @var string $filePath */
            $filePath = $file->getRealPath();
            $modelTpl = file_get_contents($filePath);
            if(false === $modelTpl) {
                throw new \RuntimeException("Cant read provided file!");
            }
            $self->registerModel($name, $modelTpl);
        }
        return $self;
    }

    static public function createEmpty(): self {
        return new self();
    }

    public function registerModel(string $name, string $modelTpl): void {
        if(isset($this->models[$name])) {
            throw new \RuntimeException(sprintf("Already registered model with given name [%s]", $name));
        }
        try {
            $this->models[$name] = json_decode($modelTpl, true, 512, JSON_THROW_ON_ERROR);
        }catch (\Throwable $e) {
            throw new \RuntimeException(sprintf("Given template cant be processed as json [%s]", $e->getMessage()));
        }
    }

    /**
     * @param string $name
     * @param int $numOfIterations
     * @return array<mixed>
     */
    public function generateModel(string $name, int $numOfIterations = 1): array {
        if(!isset($this->models[$name])) {
            throw new \RuntimeException(sprintf("Model with given name [%s] does not exist!", $name));
        }
        /** @var array<AttributeGenerator> $simpleAttributes */
        $simpleAttributes = [];
        $complexAttributes = [];
        foreach ($this->models[$name] as $attribute => $attributeDefinition) {
            if(!isset($attributeDefinition['type']) || $attributeDefinition['type'] !== 'complex') {
                $simpleAttributes[] = new AttributeGenerator(
                    $attribute,
                    $attributeDefinition['generator'] ?? (EmptyString::class).'::generate',
                    $attributeDefinition['params'] ?? [],
                    $attributeDefinition['template'] ?? "%s",
                    $attributeDefinition['modifier'] ?? []
                );
                continue;
            }
            $complexAttributes[$attribute] = $this->generateModel($attributeDefinition['model']??'', $attributeDefinition['numOfIterations']??1);
        }

        $models = [];
        for ($i=0; $i<$numOfIterations; $i++) {
            $model = \i3or1s\Model\ModelGenerator::generateModel($simpleAttributes);
            foreach ($complexAttributes as $attribute => $content) {
                if(!isset($models[$attribute])) {
                    $model[$attribute] = [];
                }
                $model[$attribute] = $content;
            }
            $models[] = $model;
        }

        return $models;
    }
}