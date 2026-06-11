<?php

declare(strict_types=1);

namespace Grasmash\YamlExpander\Tests;

use Grasmash\YamlExpander\Stringifier;
use Grasmash\YamlExpander\YamlExpander;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Psr\Log\NullLogger;
use Symfony\Component\Yaml\Yaml;

class YamlExpanderTest extends TestCase
{
    /**
     * Tests YamlExpander::expandArrayProperties().
     *
     * @param string $filename
     * @param array $reference_array
     */
    #[DataProvider('providerYaml')]
    public function testExpandArrayProperties(string $filename, array $reference_array): void
    {
        $array = Yaml::parse(file_get_contents(__DIR__ . "/../resources/$filename"));
        putenv("test=gomjabbar");
        $expander = new YamlExpander(new NullLogger());
        $expanded = $expander->expandArrayProperties($array);
        $this->assertEquals('gomjabbar', $expanded['env-test']);
        $this->assertEquals('Frank Herbert 1965', $expanded['book']['copyright']);
        $this->assertEquals('Paul Atreides', $expanded['book']['protagonist']);
        $this->assertEquals('Dune by Frank Herbert', $expanded['summary']);
        $this->assertEquals('${book.media.1}, hardcover', $expanded['available-products']);
        $this->assertEquals('Dune', $expanded['product-name']);
        $this->assertEquals(Yaml::dump($array['inline-array'], 0), $expanded['expand-array']);

        $expanded = $expander->expandArrayProperties($array, $reference_array);
        $this->assertEquals('Dune Messiah, and others.', $expanded['sequels']);
        $this->assertEquals('Dune Messiah', $expanded['book']['nested-reference']);
    }

    /**
     * Tests YamlExpander::parse().
     *
     * @param string $filename
     * @param array $reference_array
     */
    #[DataProvider('providerYaml')]
    public function testParse(string $filename, array $reference_array): void
    {
        $yaml_string = file_get_contents(__DIR__ . "/../resources/$filename");
        $expander = new YamlExpander(new NullLogger());
        $expanded = $expander->parse($yaml_string);
        $this->assertEquals('Frank Herbert 1965', $expanded['book']['copyright']);
        $this->assertEquals('Paul Atreides', $expanded['book']['protagonist']);
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
    public static function providerYaml(): array
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
     * Tests that the logger argument is optional.
     */
    public function testConstructorLoggerIsOptional(): void
    {
        $expander = new YamlExpander();
        $expanded = $expander->parse('summary: ${book.title}', ['book' => ['title' => 'Dune']]);
        $this->assertSame('Dune', $expanded['summary']);
    }

    /**
     * Tests that an empty YAML string parses to an empty array.
     */
    public function testParseEmptyString(): void
    {
        $expander = new YamlExpander(new NullLogger());
        $this->assertSame([], $expander->parse(''));
    }

    /**
     * Tests that YAML parsing to a scalar throws a clear exception.
     */
    public function testParseScalarThrows(): void
    {
        $expander = new YamlExpander(new NullLogger());
        $this->expectException(\UnexpectedValueException::class);
        $this->expectExceptionMessage('must parse to an array');
        $expander->parse('just a scalar');
    }

    /**
     * Tests that invalid YAML throws a Symfony ParseException.
     */
    public function testParseInvalidYamlThrows(): void
    {
        $expander = new YamlExpander(new NullLogger());
        $this->expectException(\Symfony\Component\Yaml\Exception\ParseException::class);
        $expander->parse("key: [unclosed");
    }

    /**
     * Tests that unresolvable placeholders are left untouched.
     */
    public function testUnresolvablePlaceholderIsLeftIntact(): void
    {
        $expander = new YamlExpander(new NullLogger());
        $expanded = $expander->parse('publisher: ${not.real.property}');
        $this->assertSame('${not.real.property}', $expanded['publisher']);
    }

    /**
     * Tests Stringifier::stringifyArray().
     */
    public function testStringifyArray(): void
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
