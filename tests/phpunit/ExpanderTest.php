<?php

namespace Grasmash\YamlExpander\Tests\Command;

use Grasmash\YamlExpander\Expander;
use Grasmash\YamlExpander\Tests\TestBase;
use Symfony\Component\Yaml\Yaml;

class ExpanderTest extends TestBase
{

    /**
     *
     *
     * @dataProvider providerTestExpandProperties
     */
    public function testExpandProperties($filename)
    {
        $array= Yaml::parse(file_get_contents(__DIR__ . '/../resources/good.yml'));
        $expanded = Expander::expandProperties($array);
        $this->assertEquals('Frank Herbert 1965', $expanded['book']['copyright']);
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
            ['good.yml'],
        ];
    }
}
