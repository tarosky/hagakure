#!/usr/bin/env bash

set -ex

composer install --no-dev
curl -L https://raw.githubusercontent.com/fumikito/wp-readme/master/wp-readme.php | php

rm ./README.md
