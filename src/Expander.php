<?php

namespace Grasmash\YamlExpander;

use Dflydev\DotAccessData\Data;

/**
 * Class Expander
 * @package Grasmash\YamlExpander
 */
class Expander
{

    public static function expandYamlProperties($yaml_string)

    {
    }


    /**
     * Expands property placeholders in the format ${parent.child}.
     *
     * @param array $array
     *   An array containing properties to expand.
     *
     * @return array
     *   The modified array in which placeholders have been replaced with
     *   values.
     */
    public static function expandProperties($array)
    {
        $data = new Data($array);
        self::doExpandProperties($data, $array);

        return $data->export();
    }

    /**
     * Performs the actual property expansion.
     *
     * @param Data $data
     *   A data object, containing the $array.
     * @param array $array
     *   The original, unmodified array.
     * @param string $parent_keys
     *   The parent keys of the current key in dot notation. This is used to
     *   track the absolute path to the current key in recursive cases.
     */
    protected static function doExpandProperties(
      $data,
      $array,
      $parent_keys = ''
    ) {
        foreach ($array as $key => $value) {
            // Boundary condition(s).
            if (is_null($value) || is_bool($value)) {
                continue;
            }

            // Recursive case.
            if (is_array($value)) {
                self::doExpandProperties($data, $value, $parent_keys . "$key");
            }
            // Base case.
            else {
                // We loop through all placeholders in a given string.
                // E.g., '${placeholder1} ${placeholder2}' requires two replacements.
                while (strpos($value, '${') !== false) {
                    $value = preg_replace_callback(
                      '/\$\{([^\$}]+)\}/',
                      function ($matches) use ($data) {
                          return self::expandPropertyCallback($matches, $data);
                      },
                      $value
                    );

                    // Set value on $data object.
                    if ($parent_keys) {
                        $full_key = $parent_keys . ".$key";
                    }
                    else {
                        $full_key = $key;
                    }
                    $data->set($full_key, $value);

                }
            }
        }
    }

    /**
     *
     *
     * @param array $matches
     * @param Data $data
     *
     * @return mixed
     */
    public static function expandPropertyCallback($matches, $data)
    {
        $propertyName = $matches[1];
        if (!$data->has($propertyName)) {
            self::log("Property \${'$propertyName'} has not been set.");
            // Return original value.
            return $matches[0];
        } else {
            $propertyValue = $data->get($propertyName);
            self::log("Expanding property \${'$propertyName'} => $propertyValue.");
        }

        return $propertyValue;
    }

    public static function log($message)
    {
        print "$message\n";
    }
}