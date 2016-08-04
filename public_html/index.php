<?php
/**
 * JBZoo PHPUnit
 *
 * This file is part of the JBZoo CCK package.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @package   PHPUnit
 * @license   MIT
 * @copyright Copyright (C) JBZoo.com,  All rights reserved.
 * @link      https://github.com/JBZoo/PHPUnit
 * @author    Denis Smetannikov <denis@jbzoo.com>
 */

// To help the built-in PHP dev server, check if the request was actually for
// something which should probably be served as a static file
if (PHP_SAPI == 'cli-server') {
    $url  = parse_url($_SERVER['REQUEST_URI']);
    $file = realpath($_SERVER['DOCUMENT_ROOT'] . $url['path']);
    if (is_file($file)) {
        return false;
    }
}

echo 'ENV_VAR: ' . getenv('ENV_VAR') . PHP_EOL;
echo ' -= OK =-' . PHP_EOL;
