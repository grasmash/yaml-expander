<?php

namespace Grasmash\YamlExpander;

use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use Symfony\Component\Yaml\Yaml;
use Grasmash\Expander\Expander as GrasmashExpander;

/**
 * Class Expander
 * @package Grasmash\YamlExpander
 */
class Expander
{
    /**
     * @var \Psr\Log\LoggerInterface
     */
    protected $logger;

    /**
     * @var \Grasmash\Expander\Expander
     */
    protected $expander;

    /**
     * Expander constructor.
     *
     * @param \Psr\Log\LoggerInterface $logger
     */
    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
        $this->expander =  new GrasmashExpander();
        $this->expander->setLogger($logger);
        $this->expander->setStringifier(new Stringifier());
    }

    /**
     * Parses a YAML string and expands property placeholders.
     *
     * Placeholders should formatted as ${parent.child}.
     *
     * @param string $yaml_string
     *   A string of YAML.
     * @param array $reference_array
     *   Optional. An array of reference values. This is not operated upon but is used as a
     *   reference to provide supplemental values for property expansion.
     *
     * @return array
     *   The modified array in which placeholders have been replaced with
     *   values.
     */
    public function parse($yaml_string, $reference_array = [])
    {
        $array = Yaml::parse($yaml_string);
        return $this->expander->expandArrayProperties($array, $reference_array);
    }

    /**
     * Expands property placeholders in an array.
     *
     * Placeholders should formatted as ${parent.child}.
     *
     * @param array $array
     *   An array containing properties to expand.
     *
     * @return array
     *   The modified array in which placeholders have been replaced with
     *   values.
     */
    public function expandArrayProperties($array, $reference_array = [])
    {
        return $this->expander->expandArrayProperties($array, $reference_array);
    }
}
