##Model Generator

This lib is used to generate models for testing purposes.

---
### Example usage
```php
<?php
require_once __DIR__."/vendor/autoload.php";

$generator = \i3or1s\ModelGenerator::createWithLibTemplates();
$models = $generator->generateModel('simpleUserModel', 2);

$file = fopen(__DIR__.'/simpleUsers.csv', 'w+');
foreach ($models as $model) {
    fputcsv($file, $model);
}
fclose($file);
```

---
### Example usage
```php
<?php
require_once __DIR__.'/vendor/autoload.php';

$modelTpl = json_decode(file_get_contents(__DIR__ . '/templates/models/simpleUserModel.json'), true);
/** @var array<\i3or1s\Model\AttributeGenerator> $attributes */
$attributes = [];
foreach ($modelTpl as $attribute => $attributeDefinition) {
    $attributes[] = new \i3or1s\Model\AttributeGenerator($attribute, $attributeDefinition['generator'], $attributeDefinition['params'] ?? [], $attributeDefinition['template'], $attributeDefinition['modifier']??[]);
}

$file = fopen(__DIR__.'/simpleUsers.csv', 'w+');
for ($i=0; $i<3; $i++) {
    fputcsv($file, \i3or1s\Model\ModelGenerator::generateModel($attributes));
}
fclose($file);
```