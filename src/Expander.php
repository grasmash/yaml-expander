<?php

namespace Grasmash\YamlExpander;

use Dflydev\DotAccessData\Data;

class Expander {

    public static function expandYamlProperties($yaml_string) {
    }


    /**
     * @param $array
     */
    public static function expandProperties($array) {
        $data = new Data($array);
        self::doExpandProperties($data, $array);

        return $data->export();
    }

    /**
     * @param Data $data
     * @param $array
     */
    protected static function doExpandProperties($data, $array, $parent_keys = '') {
        foreach ($array as $key => $value) {
            // Boundary condition(s).
            if (is_null($value)) {
                continue;
            }

            // Recursive case.
            if (is_array($value)) {
                self::doExpandProperties($data, $value, $parent_keys . "$key");
            }
            // Base case.
            else {
                // We loop through all placeholders in a given string.
                // E.g., '${placeholder1} ${placeholder2}.
                $full_key = $parent_keys . ".$key";
                while (strpos($value, '${') !== false) {
                    $value = preg_replace_callback(
                      '/\$\{([^\$}]+)\}/',
                      function($matches) use ($data) {
                          return self::expandPropertyCallback($matches, $data);
                      },
                      $value
                    );
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
    public static function expandPropertyCallback($matches, $data) {
        $propertyName = $matches[1];
        if (!$data->has($propertyName)) {
            self::log('Property ${' . $propertyName . '} has not been set.');
            // Return original value.
            return $matches[0];
        } else {
            $propertyValue = $data->get($propertyName);
            self::log('Property ${' . $propertyName . '} => ' . $propertyValue);
        }

        return $propertyValue;
    }

    public static function log($message) {
        print $message;
    }
}