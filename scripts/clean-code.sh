#!/usr/bin/env bash

set -x

./vendor/bin/phpcbf --standard=PSR2 src --exclude=Generic.Files.LineLength
./vendor/bin/phpcbf --standard=PSR2 tests --exclude=Generic.Files.LineLength