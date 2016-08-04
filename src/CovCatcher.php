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

use JBZoo\Data\Data;
use JBZoo\Utils\Env;
use JBZoo\Utils\Str;
use SebastianBergmann\CodeCoverage\CodeCoverage;
use SebastianBergmann\CodeCoverage\Filter;
use SebastianBergmann\CodeCoverage\Report\PHP as ReportPHP;
use SebastianBergmann\CodeCoverage\Report\Clover as ReportClover;
use SebastianBergmann\CodeCoverage\Report\Html\Facade as ReportHtml;

/**
 * Class CovCatcher
 * @package JBZoo\PHPUnit
 */
class CovCatcher
{
    const MODE_REQUIRE      = 'require';
    const MODE_REQUIRE_ONCE = 'require_once';

    protected $_isStart = false;

    /**
     * @var CodeCoverage
     */
    protected $_coverage;

    /**
     * @var array
     */
    protected $_default = array(
        'xml'        => true,
        'cov'        => false,
        'html'       => false,
        'src'        => './src',
        'build_xml'  => './build/clover_xml',
        'build_cov'  => './build/clover_cov',
        'build_html' => './build/clover_html',
    );

    /**
     * @var Data
     */
    protected $_config;

    /**
     * @var string
     */
    protected $_hash;

    /**
     * CovCatcher constructor.
     * @param string $testName
     * @param array  $options
     */
    public function __construct($testName = null, array $options = array())
    {
        $this->_config = new Data(array_merge($this->_default, $options));
        $this->_hash   = $this->_getPrefix($testName)
            . '_' . md5(serialize($this->_config->getArrayCopy()) . '|' . $testName);

        if (Env::hasXdebug()) {
            $covFilter = new Filter();
            $covFilter->addDirectoryToWhitelist($this->_config->get('src'));
            $this->_coverage = new CodeCoverage(null, $covFilter);
        }
    }

    /**
     * @param string $filename
     * @param string $mode
     * @return mixed
     * @throws \Exception
     */
    public function includeFile($filename, $mode = self::MODE_REQUIRE)
    {
        $this->_start();

        $realpath = realpath($filename);
        if (!file_exists($realpath)) {
            throw new \Exception("Included file not found: \"{$filename}\"");
        }

        if (self::MODE_REQUIRE === $mode) {
            $result = require $realpath;

        } elseif (self::MODE_REQUIRE_ONCE === $mode) {
            $result = require_once $realpath;

        } else {
            throw new \Exception("Undefined mode to include file: \"{$filename}\"");
        }

        $this->_stop();

        return $result;
    }

    /**
     * @param null|string $testName
     * @return string
     */
    protected function _getPrefix($testName = null)
    {
        $testName = str_replace(__NAMESPACE__ . '\\', '', $testName);
        $testName = Str::splitCamelCase($testName, '_', true);
        $testName = preg_replace('/^test_/', '', $testName);
        $testName = preg_replace('/_test$/', '', $testName);
        $testName = str_replace('_test_test_', '_', $testName);
        $testName = str_replace(array('/', '\\'), '_', $testName);
        $testName = strtolower($testName);

        return $testName;
    }

    /**
     * Save report
     */
    public function __destruct()
    {
        $this->_stop();
        $this->_createReports();
    }

    /**
     * Start coverage proccess
     */
    protected function _start()
    {
        if (!$this->_isStart && $this->_coverage) {
            $this->_isStart = true;
            //$this->_coverage->start($this->_hash, true);
        }
    }

    /**
     * Stop or pause coverage proccess
     */
    protected function _stop()
    {
        if ($this->_isStart && $this->_coverage) {
            $this->_isStart = false;
            //$this->_coverage->stop();
        }
    }

    /**
     * Stop or pause coverage proccess
     */
    protected function _createReports()
    {
        return;
        if (!$this->_coverage) {
            return;
        }

        $reportXmlDir = $this->_config->get('build_xml');
        if ($this->_config->get('xml', true, 'bool')) {
            $this->_checkDir($reportXmlDir);
            $report = new ReportClover();
            $report->process($this->_coverage, $reportXmlDir . '/' . $this->_hash . '.xml');
        }


        $reportCovDir = $this->_config->get('build_cov');
        if ($this->_config->get('cov', false, 'bool')) {
            $this->_checkDir($reportCovDir);
            $report = new ReportPHP();
            $report->process($this->_coverage, $reportCovDir . '/' . $this->_hash . '.cov');
        }


        $reportHtmlDir = $this->_config->get('build_html');
        if ($this->_config->get('html', false, 'bool')) {
            $this->_checkDir($reportHtmlDir);
            $report = new ReportHtml();
            $report->process($this->_coverage, $reportHtmlDir . '/' . $this->_hash);
        }
    }

    /**
     * @param string $dirPath
     */
    protected function _checkDir($dirPath)
    {
        if (!is_dir($dirPath)) {
            mkdir($dirPath, 0777, true);
        }
    }
}