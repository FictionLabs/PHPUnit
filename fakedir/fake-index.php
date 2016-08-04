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
echo __LINE__ . PHP_EOL;
use JBZoo\PHPUnit\CovCatcher;

echo __LINE__ . PHP_EOL;
if (PHP_SAPI == 'cli-server') {
    $url  = parse_url($_SERVER['REQUEST_URI']);
    $file = realpath($_SERVER['DOCUMENT_ROOT'] . $url['path']);
    echo __LINE__ . PHP_EOL;
    if (is_file($file)) {
        echo __LINE__ . PHP_EOL;
        return false;
    }
}

echo __LINE__ . PHP_EOL;

require_once __DIR__ . '/../vendor/autoload.php';

echo __LINE__ . PHP_EOL;

$catcher = new CovCatcher('test', array(
    'src'  => __DIR__ . '/../public_html',
    'xml'  => true,
    'html' => true,
    'cov'  => true,
));

echo __LINE__ . PHP_EOL;
$catcher->includeFile(__DIR__ . '/../public_html/index.php');
echo __LINE__ . PHP_EOL;
