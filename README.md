[![CI](https://github.com/grasmash/yaml-expander/actions/workflows/php.yml/badge.svg)](https://github.com/grasmash/yaml-expander/actions/workflows/php.yml) [![Packagist](https://img.shields.io/packagist/v/grasmash/yaml-expander.svg)](https://packagist.org/packages/grasmash/yaml-expander)
[![Total Downloads](https://poser.pugx.org/grasmash/yaml-expander/downloads)](https://packagist.org/packages/grasmash/yaml-expander) [![Coverage Status](https://coveralls.io/repos/github/grasmash/yaml-expander/badge.svg?branch=main)](https://coveralls.io/github/grasmash/yaml-expander?branch=main)

This tool expands property references in YAML files.

### Requirements

* PHP 8.1+

### Installation

    composer require grasmash/yaml-expander

### Example usage:

Example dune.yml:

```yaml
type: book
book:
  title: Dune
  author: Frank Herbert
  copyright: ${book.author} 1965
  protagonist: ${characters.0.name}
  media:
    - hardcover
characters:
  - name: Paul Atreides
    occupation: Kwisatz Haderach
    aliases:
      - Usul
      - Muad'Dib
      - The Preacher
  - name: Duncan Idaho
    occupation: Swordmaster
summary: ${book.title} by ${book.author}
product-name: ${${type}.title}
timezone: ${env.TZ}
```

Property references use dot notation to indicate array keys, and must be wrapped in `${}`.

Expansion logic:

```php
<?php

use Grasmash\YamlExpander\YamlExpander;
use Symfony\Component\Yaml\Yaml;

// Set an environmental variable, accessible via ${env.TZ}.
putenv("TZ=ES");

// Optionally pass any PSR-3 logger to see which placeholders were expanded
// or could not be resolved. Defaults to a no-op logger.
$expander = new YamlExpander();

// Parse a yaml string directly, expanding internal property references.
$yaml_string = file_get_contents("dune.yml");
$expanded = $expander->parse($yaml_string);
print_r($expanded);

// Parse an array, expanding internal property references.
$array = Yaml::parse(file_get_contents("dune.yml"));
$expanded = $expander->expandArrayProperties($array);
print_r($expanded);

// Expand references using both internal and supplementary values:
// a placeholder such as ${book.sequel} that exists nowhere in the YAML
// itself is resolved from the reference array.
$yaml_string = "sequels: \${book.sequel}, and others.";
$reference_properties = ['book' => ['sequel' => 'Dune Messiah']];
$expanded = $expander->parse($yaml_string, $reference_properties);
// $expanded['sequels'] === 'Dune Messiah, and others.'
```

Unresolvable placeholders are left intact (and reported to the logger).
Invalid YAML throws `Symfony\Component\Yaml\Exception\ParseException`, and
YAML that parses to a non-array value (e.g., a bare scalar) throws
`UnexpectedValueException`.

### Security

Only parse YAML from trusted sources. Placeholder expansion can reference any
value in the document or reference array, including environment variables via
`${env.*}`, and deeply self-referencing placeholders can expand to very large
strings. This library is designed for trusted configuration files, not for
arbitrary user-supplied input.

Resultant array:

```php
<?php

array (
  'type' => 'book',
  'book' =>
  array (
    'title' => 'Dune',
    'author' => 'Frank Herbert',
    'copyright' => 'Frank Herbert 1965',
    'protagonist' => 'Paul Atreides',
    'media' =>
    array (
      0 => 'hardcover',
    ),
  ),
  'characters' =>
  array (
    0 =>
    array (
      'name' => 'Paul Atreides',
      'occupation' => 'Kwisatz Haderach',
      'aliases' =>
      array (
        0 => 'Usul',
        1 => 'Muad\'Dib',
        2 => 'The Preacher',
      ),
    ),
    1 =>
    array (
      'name' => 'Duncan Idaho',
      'occupation' => 'Swordmaster',
    ),
  ),
  'summary' => 'Dune by Frank Herbert',
  'product-name' => 'Dune',
  'timezone' => 'ES',
);
```

### Development

Local development commands:

    composer install
    composer test      # lint + unit tests + code sniffer
    composer unit      # PHPUnit only
    composer cs        # code sniffer only
    composer cbf       # auto-fix code style
    composer coverage  # generate clover coverage report
