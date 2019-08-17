<?php

namespace Grasmash\YamlExpander\Tests\Command;

use Dflydev\DotAccessData\Data;
use Grasmash\YamlExpander\Expander;
use Grasmash\YamlExpander\Stringifier;
use Grasmash\YamlExpander\Tests\TestBase;
use Psr\Log\NullLogger;
use Symfony\Component\Yaml\Yaml;

class ExpanderTest extends \PHPUnit_Framework_TestCase
{

    /**
     * Tests Expander::expandArrayProperties().
     *
     * @param string $filename
     * @param array $reference_array
     *
     * @dataProvider providerYaml
     */
    public function testExpandArrayProperties($filename, $reference_array)
    {
        $array = Yaml::parse(file_get_contents(__DIR__ . "/../resources/$filename"));
        putenv("test=gomjabbar");
        $expander = new Expander(new NullLogger());
        $expanded = $expander->expandArrayProperties($array);
        $this->assertEquals('gomjabbar', $expanded['env-test']);
        $this->assertEquals('Frank Herbert 1965', $expanded['book']['copyright']);
        $this->assertEquals('Paul Atreides', $expanded['book']['protaganist']);
        $this->assertEquals('Dune by Frank Herbert', $expanded['summary']);
        $this->assertEquals('${book.media.1}, hardcover', $expanded['available-products']);
        $this->assertEquals('Dune', $expanded['product-name']);
        $this->assertEquals(Yaml::dump($array['inline-array'], 0), $expanded['expand-array']);

        $expanded = $expander->expandArrayProperties($array, $reference_array);
        $this->assertEquals('Dune Messiah, and others.', $expanded['sequels']);
        $this->assertEquals('Dune Messiah', $expanded['book']['nested-reference']);
    }

    /**
     * Tests Expander::parse().
     *
     * @param string $filename
     * @param array $reference_array
     *
     * @dataProvider providerYaml
     */
    public function testParse($filename, $reference_array)
    {
        $yaml_string = file_get_contents(__DIR__ . "/../resources/$filename");
        $expander = new Expander(new NullLogger());
        $expanded = $expander->parse($yaml_string);
        $this->assertEquals('Frank Herbert 1965', $expanded['book']['copyright']);
        $this->assertEquals('Paul Atreides', $expanded['book']['protaganist']);
        $this->assertEquals('Dune by Frank Herbert', $expanded['summary']);
        $this->assertEquals('${book.media.1}, hardcover', $expanded['available-products']);

        $expanded = $expander->parse($yaml_string, $reference_array);
        $this->assertEquals('Dune Messiah, and others.', $expanded['sequels']);
        $this->assertEquals('Dune Messiah', $expanded['book']['nested-reference']);
    }

    /**
     * @return array
     *   An array of values to test.
     */
    public function providerYaml()
    {
        return [
          ['valid.yml', [
            'book' => [
              'sequel' => 'Dune Messiah'
            ]
          ]],
        ];
    }

    /**
     * Tests Expander::expandProperty().
     */
    public function testStringifyArray()
    {
        $array =  [
          0 => 'one',
          1 => 'two',
          2 => 'three',
        ];
        $string = Stringifier::stringifyArray($array);
        $this->assertEquals('[one, two, three]', $string);
    }
}
