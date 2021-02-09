<?php
declare(strict_types=1);

namespace i3or1s\Model;


class ModelGenerator
{
    /**
     * @param array<AttributeGenerator> $attributes
     * @return array<string, string|int|bool|array<mixed>>
     */
    static public function generateModel(array $attributes): array
    {
        $prepareTemplateParams = [];
        foreach ($attributes as $attribute) {
            $prepareTemplateParams[$attribute->attributeName()] = $attribute->attributeValue();
        }
        $response = [];
        foreach ($attributes as $attribute) {
            $response[$attribute->attributeName()] = $attribute->transform($prepareTemplateParams[$attribute->attributeName()], $prepareTemplateParams);
        }
        return $response;
    }
}