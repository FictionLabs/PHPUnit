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

// @codingStandardsIgnoreFile
!defined('JBZOO_PHPUNIT') && define('JBZOO_PHPUNIT', true);

// PHP 5.3.3 does not support this
!defined('DEBUG_BACKTRACE_PROVIDE_OBJECT') && define('DEBUG_BACKTRACE_PROVIDE_OBJECT', true);

// System
!defined('CRLF') && define('CRLF', "\r\n");
!defined('LF') && define('LF', "\n");
!defined('DS') && define('DS', DIRECTORY_SEPARATOR);

// Paths
!defined('PROJECT_ROOT') && define('PROJECT_ROOT', realpath('.'));
!defined('PROJECT_BUILD') && define('PROJECT_BUILD', PROJECT_ROOT . DS . 'build');
!defined('PROJECT_SRC') && define('PROJECT_SRC', PROJECT_ROOT . DS . 'src');
!defined('PROJECT_TESTS') && define('PROJECT_TESTS', PROJECT_ROOT . DS . 'tests');
!defined('PROJECT_RESOURCES') && define('PROJECT_RESOURCES', PROJECT_ROOT . DS . 'resources');