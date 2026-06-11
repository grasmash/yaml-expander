# Contributing

Thanks for your interest in contributing!

## Getting started

1. Fork and clone the repository.
2. Install dependencies:

       composer install

## Development workflow

* Run the full test suite (lint, unit tests, and code sniffer):

      composer test

* Auto-fix code style issues:

      composer cbf

## Pull requests

* Include tests for any change in behavior.
* Keep the code style passing (`composer cs`); the project follows PSR-2.
* Open pull requests against the `main` branch.
