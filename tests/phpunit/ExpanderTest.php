<?php

namespace Grasmash\YamlExpander\Tests\Command;

use Grasmash\YamlExpander\Expander;
use Grasmash\YamlExpander\Tests\TestBase;
use Symfony\Component\Yaml\Yaml;

class ExpanderTest extends TestBase
{

    /**
     * @param string $filename
     *
     * @dataProvider providerTestExpandProperties
     */
    public function xtestExpandProperties($filename)
    {
        $array= Yaml::parse(file_get_contents(__DIR__ . "/../resources/$filename"));
        $expanded = Expander::expandArrayProperties($array);
        $this->assertEquals('Frank Herbert 1965', $expanded['book']['copyright']);
        $this->assertEquals('Paul Atreides', $expanded['book']['protaganist']);
        $this->assertEquals('Dune by Frank Herbert', $expanded['summary']);
    }

    /**
     * Provides values to testApplication().
     *
     * @return array
     *   An array of values to test.
     */
    public function providerTestExpandProperties()
    {
        return [
          ['valid.yml'],
        ];
    }

    /**
     * @param string $filename
     * @param array $reference_array
     *
     * @dataProvider providerTestsExpandReferenceProperties
     */
    public function testsExpandReferenceProperties($filename, $reference_array) {
        $array= Yaml::parse(file_get_contents(__DIR__ . "/../resources/$filename"));
        $expanded = Expander::expandArrayProperties($array, $reference_array);
        $this->assertEquals('Frank Herbert 1965', $expanded['book']['copyright']);
        $this->assertEquals('Paul Atreides', $expanded['book']['protaganist']);
        $this->assertEquals('Dune by Frank Herbert', $expanded['summary']);
        $this->assertEquals('Dune Messiah, and others.', $expanded['sequels']);
    }

    /**
     * Provides values to testApplication().
     *
     * @return array
     *   An array of values to test.
     */
    public function providerTestsExpandReferenceProperties()
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
