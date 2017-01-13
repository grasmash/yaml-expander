[![Build Status](https://travis-ci.org/grasmash/yaml-cli.svg?branch=master)](https://travis-ci.org/grasmash/yaml-cli) [![Packagist](https://img.shields.io/packagist/v/grasmash/yaml-cli.svg)](https://packagist.org/packages/grasmash/yaml-cli)

Yet another  command line tool for reading and manipulating yaml files, built on the [Symfony console component](http://symfony.com/doc/current/components/console.html).

### Commands:


| Command      | Description                                         |
|--------------| ----------------------------------------------------|
| get:value    | Get a value for a specific key in a YAML file.      |
| lint         | Validates that a given YAML file has valid syntax.  |
| unset:key    | Unset a specific key in a YAML file.                |
| update:key   | Change a specific key in a YAML file.               |
| update:value | Update the value for a specific key in a YAML file. |

### Installation

    composer require grasmash/yaml-cli

### Example usage:

    ./vendor/bin/yaml-cli get:value somefile.yml some-key
    ./vendor/bin/yaml-cli lint somefile.yml
    ./vendor/bin/yaml-cli unset:value somefile.yml some-key
    ./vendor/bin/yaml-cli update:key somefile.yml old-key new-key
    ./vendor/bin/yaml-cli update:value somefile.yml some-key some-value

### Similar tools:

- Javascript - https://github.com/pandastrike/yaml-cli
- Ruby - https://github.com/rubyworks/yaml_command
- Python - https://github.com/0k/shyaml

### Recognition

This project was inspired by the yaml commands in [Drupal Console](https://drupalconsole.com/).