<?php
declare(strict_types=1);

namespace i3or1s\Model;

class AttributeGenerator
{
    private string $attributeName;

    /** @var callable-string  */
    private string $generator;

    /** @var array<string, string|int|bool>  */
    private array $params = [];

    /** @var string|array<string>  */
    private string|array $template;

    /** @var array<callable-string>  */
    private array $modifier;

    /**
     * @param string $attributeName
     * @param callable-string $generator
     * @param bool[]|int[]|string[] $params
     * @param string|string[] $template
     * @param array<callable-string> $modifier
     */
    public function __construct(string $attributeName, string $generator, array $params, array|string $template, array $modifier)
    {
        $this->attributeName = $attributeName;
        if(!self::isValidGenerator($generator)) {
            throw new \RuntimeException("Not a valid generator! Must be callable!");
        }
        $this->generator = $generator;
        if(!self::areParamsValid($generator, $params)) {
            throw new \RuntimeException("Not a valid parameters!");
        }
        $this->params = $params;
        $this->template = $template;
        if(!self::areModifiersValid($modifier)) {
            throw new \RuntimeException("Not a valid parameters!");
        }
        $this->modifier = $modifier;
    }

    /**
     * @param array<string> $modifiers
     * @return bool
     */
    static private function areModifiersValid(array $modifiers): bool {
        foreach ($modifiers as $modifier) {
            if(!is_callable($modifier)) {
                return false;
            }
        }
        return true;
    }

    static private function isValidGenerator(string $generator): bool {
        return is_callable($generator);
    }

    /**
     * @param string $generator
     * @param array<string, mixed> $params
     * @return bool
     */
    static private function areParamsValid(string $generator, array $params): bool {
        try {
            $reflection = function_exists($generator) ? new \ReflectionFunction($generator) : new \ReflectionMethod($generator);

            foreach($reflection->getParameters() as $reflectionParameter) {

                if($reflectionParameter->isDefaultValueAvailable()
                    && $reflectionParameter->isOptional()
                    && !isset($params[$reflectionParameter->getName()])
                ) {
                    continue;
                }
                if(!isset($params[$reflectionParameter->getName()])) {
                    return false;
                }
                $parameterType = gettype($params[$reflectionParameter->getName()]);
                $parameterType = str_replace(["boolean", "integer"], ["bool", "int"], $parameterType);
                $type = $reflectionParameter->getType() === null ? 'NULL' : (string)$reflectionParameter->getType();
                if(false === str_contains(strtolower($type), strtolower($parameterType))) {
                    return false;
                }
                unset($params[$reflectionParameter->getName()]);
            }
            if(count($params) > 0) {
                return false;
            }
        } catch (\Throwable) {
            return false;
        }

        return true;
    }

    public function attributeValue(): string {
        return (string)($this->generator)(...$this->params);
    }

    public function attributeName(): string {
        return $this->attributeName;
    }

    /**
     * @param array<string, string> $params
     * @param string $attributeValue
     * @return string|int|bool|array<mixed>
     */
    public function transform(string $attributeValue, array $params = []): string|int|bool|array {
        try {
            if(is_string($this->template)) {
                $templateFilled = self::populateTemplate($this->template, $attributeValue, $params);
                return self::performModifications($templateFilled, $this->modifier);
            }

            $result = [];
            foreach($this->template as $tpl) {
                $templateFilled = self::populateTemplate($tpl, $attributeValue, $params);
                $result[] = self::performModifications($templateFilled, $this->modifier);
            }

            return $result;
        } catch (\Throwable) {
        }
        if(is_string($this->template)) {
            return $attributeValue;
        }
        return [$attributeValue];
    }

    /**
     * @param string $template
     * @param mixed $attributeValue
     * @param array<string, mixed> $templateParams
     * @return string
     */
    static private function populateTemplate(string $template, mixed $attributeValue, array $templateParams): string {
        try {
            preg_match_all('#({.*?})#', $template, $matches);
            $params = [];
            try {
                $template = sprintf($template, $attributeValue);
            }catch (\Throwable){}
            if(count($matches[0]) > 0) {
                foreach ($matches[0] as $templateParam) {
                    if(isset($templateParams[trim($templateParam, '{}')])){
                        $params[] = $templateParams[trim($templateParam, '{}')];
                        $template = str_replace($templateParam, '%s', $template);
                    }
                }
            }
            if(count($params) > 0) {
                $template = sprintf($template, ...$params);
            }
            return $template;

        }catch (\Throwable $e) {
            throw new \RuntimeException(sprintf('Invalid model template format <%s> - %s', $template, $e->getMessage()));
        }
    }

    /**
     * @param string $string
     * @param array<callable-string> $modifications
     * @return string|int|bool
     */
    static private function performModifications(string $string, array $modifications): string|int|bool {
        foreach ($modifications as $modifier) {
            $string = $modifier($string);
        }

        return $string;
    }
}
