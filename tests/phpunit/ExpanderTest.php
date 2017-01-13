<?php

namespace Grasmash\YamlExpander\Tests\Command;

use Grasmash\YamlExpander\Expander;
use Grasmash\YamlExpander\Tests\TestBase;
use Symfony\Component\Yaml\Yaml;

class ExpanderTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @param string $filename
     * @param array $reference_array
     *
     * @dataProvider providerYaml
     */
    public function testExpandProperties($filename, $reference_array)
    {
        $array = Yaml::parse(file_get_contents(__DIR__ . "/../resources/$filename"));
        $expanded = Expander::expandArrayProperties($array);
        $this->assertEquals('Frank Herbert 1965', $expanded['book']['copyright']);
        $this->assertEquals('Paul Atreides', $expanded['book']['protaganist']);
        $this->assertEquals('Dune by Frank Herbert', $expanded['summary']);
        $this->assertEquals('${book.media.1}, hardcover', $expanded['available-products']);
        $this->assertEquals('Dune', $expanded['product-name']);

        $expanded = Expander::expandArrayProperties($array, $reference_array);
        $this->assertEquals('Dune Messiah, and others.', $expanded['sequels']);
    }

    /**
     * @param $filename
     *
     * @dataProvider providerYaml
     */
    public function testParse($filename, $reference_array)
    {
        $yaml_string = file_get_contents(__DIR__ . "/../resources/$filename");
        $expanded = Expander::parse($yaml_string);
        $this->assertEquals('Frank Herbert 1965', $expanded['book']['copyright']);
        $this->assertEquals('Paul Atreides', $expanded['book']['protaganist']);
        $this->assertEquals('Dune by Frank Herbert', $expanded['summary']);
        $this->assertEquals('${book.media.1}, hardcover', $expanded['available-products']);

        $expanded = Expander::parse($yaml_string, $reference_array);
        $this->assertEquals('Dune Messiah, and others.', $expanded['sequels']);
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
}
