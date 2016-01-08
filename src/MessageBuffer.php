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

/**
 * Class MessageCollector
 * @package JBZoo\PHPUnit
 */
class MessageBuffer
{
    /**
     * @var array
     */
    protected $_info = array();

    /**
     * @var array
     */
    protected $_error = array();

    /**
     * @return MessageBuffer
     */
    public static function getInstance()
    {
        static $instance;

        if (!isset($instance)) {
            $instance = new self();
        }

        return $instance;
    }

    /**
     * @param string $message
     */
    public function info($message)
    {
        $this->_info[] = $message;
    }

    /**
     * @param string $message
     */
    public function error($message)
    {
        $this->_error[] = $message;
    }

    /**
     * Show messages only on php script die!
     */
    public function __destruct()
    {
        //@codeCoverageIgnoreStart
        foreach ($this->_info as $message) {
            if (defined('STDOUT')) {
                fwrite(STDOUT, $message);
            } else {
                echo $message;
            }
        }

        foreach ($this->_error as $message) {
            if (defined('STDERR')) {
                fwrite(STDERR, $message);
            } else {
                echo $message;
            }
        }
        //@codeCoverageIgnoreEnd
    }
}