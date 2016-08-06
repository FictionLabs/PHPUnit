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

namespace JBZoo\PHPUnit;

use JBZoo\Utils\Arr;
use JBZoo\Utils\FS;
use JBZoo\Utils\Vars;

/**
 * Class BenchmarkTest
 * @package JBZoo\PHPUnit
 */
class BenchmarkTest extends PHPUnit
{
    public function testBenchmarkMemory()
    {
        runBench(array(
            'x1'  => function () {
                return str_repeat(mt_rand(0, 9), 900000);
            },
            'x2'  => function () {
                return str_repeat(mt_rand(0, 9), 900000 * 2);
            },
            'x3'  => function () {
                return str_repeat(mt_rand(0, 9), 900000 * 3);
            },
            'x16' => function () {
                return str_repeat(mt_rand(0, 9), 900000 * 16);
            },
        ), array('name' => 'runBench()'));
    }

    public function testFunctionWrapper()
    {
        $myTrim = function ($string) {
            return trim($string);
        };

        $source = "\t" . '  trim ..   ' . "\t\n";
        $obj    = $this;

        runBench(array(
            'clean'           => function () use ($source) {
                return trim($source);
            },
            '$obj->_myTrim()' => function () use ($source, $obj) {
                return $obj->myTrim($source);
            },
            '$myTrim()'       => function () use ($source, $myTrim) {
                return $myTrim($source);
            },
            'myTrim()'        => function () use ($source) {
                return myTrim($source);
            },
            '..\myTrim()'     => function () use ($source) {
                return \JBZoo\PHPUnit\myTrim($source);
            },
        ), array('name' => 'Function wrapper overhead', 'count' => 10000));
    }

    public function testFunctionOverhead()
    {
        runBench(array(
            'Clean'   => function () {
                return pathinfo(__FILE__, PATHINFO_BASENAME);
            },
            'Wrapper' => function () {
                return FS::base(__FILE__);
            },
        ), array('name' => 'Pathinfo overhead', 'count' => 10000));

        runBench(array(
            'Vars::get' => function () {
                return Vars::get($GLOBALS['somevar']);
            },
            'isset'     => function () {
                return isset($GLOBALS['somevar']);
            },
        ), array('name' => 'Isset overhead', 'count' => 10000));


        $randArr = array_fill(0, 100, null);

        for ($i = 0; $i < 100; $i += 1) {
            $randArr[$i] = mt_rand(0, 9);
        }

        runBench(array(
            'array_keys(+flip)' => function () use ($randArr) {
                return Arr::unique($randArr, false);
            },
            'array_unique'      => function () use ($randArr) {
                return Arr::unique($randArr, true);
            },
        ), array('name' => 'Isset overhead', 'count' => 1000));
    }

    /**
     * @param $string
     * @return string
     */
    public function myTrim($string)
    {
        return trim($string);
    }
}
