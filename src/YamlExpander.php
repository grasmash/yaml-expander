<?php

declare(strict_types=1);

namespace Grasmash\YamlExpander;

use Grasmash\Expander\Expander;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use Symfony\Component\Yaml\Yaml;

/**
 * Expands property placeholders in YAML strings and arrays.
 */
class YamlExpander
{
    protected LoggerInterface $logger;

    protected Expander $expander;

    /**
     * YamlExpander constructor.
     *
     * @param \Psr\Log\LoggerInterface|null $logger
     *   Optional. The logger to which expansion notices are written. Defaults
     *   to a no-op logger.
     */
    public function __construct(?LoggerInterface $logger = null)
    {
        $this->logger = $logger ?? new NullLogger();
        $this->expander = new Expander();
        $this->expander->setLogger($this->logger);
        $this->expander->setStringifier(new Stringifier());
    }

    /**
     * Parses a YAML string and expands property placeholders.
     *
     * Placeholders should be formatted as ${parent.child}.
     *
     * @param string $yaml_string
     *   A string of YAML.
     * @param array $reference_array
     *   Optional. An array of reference values. This is not operated upon but
     *   is used as a reference to provide supplemental values for property
     *   expansion.
     *
     * @return array
     *   The parsed array in which placeholders have been replaced with values.
     *
     * @throws \Symfony\Component\Yaml\Exception\ParseException
     *   If the string is not valid YAML.
     * @throws \UnexpectedValueException
     *   If the YAML parses to a non-array value.
     */
    public function parse(string $yaml_string, array $reference_array = []): array
    {
        $array = Yaml::parse($yaml_string);
        if ($array === null) {
            return [];
        }
        if (!is_array($array)) {
            throw new \UnexpectedValueException(
                'The provided YAML must parse to an array, got ' . get_debug_type($array) . '.'
            );
        }
        return $this->expander->expandArrayProperties($array, $reference_array);
    }

    /**
     * Expands property placeholders in an array.
     *
     * Placeholders should be formatted as ${parent.child}.
     *
     * @param array $array
     *   An array containing properties to expand.
     * @param array $reference_array
     *   Optional. An array of reference values. This is not operated upon but
     *   is used as a reference to provide supplemental values for property
     *   expansion.
     *
     * @return array
     *   The modified array in which placeholders have been replaced with
     *   values.
     */
    public function expandArrayProperties(array $array, array $reference_array = []): array
    {
        return $this->expander->expandArrayProperties($array, $reference_array);
    }
}
