<?php

namespace Grasmash\YamlExpander;

use Grasmash\Expander\StringifierInterface;
use Symfony\Component\Yaml\Yaml;

class Stringifier implements StringifierInterface
{
    /**
     * Converts array to string.
     *
     * @param array $array
     *   The array to convert.
     *
     * @return string
     *   The resultant string.
     */
    public static function stringifyArray(array $array): string
    {
        return Yaml::dump($array, 0);
    }
}
