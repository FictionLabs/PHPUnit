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

ENV_VAR=42 php -S "localhost:8888" -t "./public_html" "./public_html/index.php" &

sleep 3s
