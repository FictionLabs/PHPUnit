#!/usr/bin/env sh

#
# JBZoo PHPUnit
#
# This file is part of the JBZoo CCK package.
# For the full copyright and license information, please view the LICENSE
# file that was distributed with this source code.
#
# @package   PHPUnit
# @license   MIT
# @copyright Copyright (C) JBZoo.com,  All rights reserved.
# @link      https://github.com/JBZoo/PHPUnit
#

echo $1;
echo $2;
echo $3;
echo $4;
echo $5;

ENV_VAR=$5 php -S "$1:$2" -t "$3" "$4" &

sleep 3s
