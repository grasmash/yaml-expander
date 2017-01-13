#!/usr/bin/env bash

set -x

./vendor/bin/phpcs --standard=PSR2 src --exclude=Generic.Files.LineLength
./vendor/bin/phpcs --standard=PSR2 tests --exclude=Generic.Files.LineLength
# Running phpunit globally can cause Composer autoloading to fail.
./vendor/bin/phpunit