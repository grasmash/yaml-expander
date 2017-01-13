<?php

namespace Grasmash\YamlExpander\Tests\Command;

use Grasmash\YamlExpander\Expander;
use Grasmash\YamlExpander\Tests\TestBase;
use Symfony\Component\Yaml\Yaml;

class ExpanderTest extends TestBase
{

    /**
     * @param $filename
     *
     * @dataProvider providerTestExpandProperties
     */
    public function testExpandProperties($filename)
    {
        $array= Yaml::parse(file_get_contents(__DIR__ . "/../resources/$filename"));
        $expanded = Expander::expandProperties($array);
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
}
