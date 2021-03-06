<?php

/**
 * JBZoo Toolbox - PHPUnit
 *
 * This file is part of the JBZoo Toolbox project.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @package    PHPUnit
 * @license    MIT
 * @copyright  Copyright (C) JBZoo.com, All rights reserved.
 * @link       https://github.com/JBZoo/PHPUnit
 * @author     Denis Smetannikov <denis@jbzoo.com>
 */

namespace JBZoo\PHPUnit;

use Symfony\Component\Finder\Finder;

/**
 * Class AbstractCodestyleTest
 *
 * @package JBZoo\PHPUnit
 */
abstract class AbstractCodestyleTest extends PHPUnit
{
    #### Configurations. Override it if you need for your project. #####################################################

    /**
     * @var string
     */
    protected $projectRoot = PROJECT_ROOT;

    /**
     * @var string[]
     */
    protected $excludePaths = [
        '.git',
        '.idea',
        'bin',
        'bower_components',
        'build',
        'fonts',
        'fixtures',
        'logs',
        'node_modules',
        'resources',
        'vendor',
        'temp',
        'tmp',
    ];

    /**
     * @throws \Exception
     */
    protected function setUp(): void
    {
        parent::setUp();

        if (!$this->projectRoot) {
            throw new Exception('$this->projectRoot is undefined!');
        }

        if (!class_exists(Finder::class)) {
            throw new Exception('symfony/finder is required for CodeStyle unit tests');
        }
    }

    #### Test cases ####################################################################################################

    public function testFiles(): void
    {
        $finder = (new Finder())
            ->files()
            ->in($this->projectRoot)
            ->exclude($this->excludePaths)
            ->ignoreDotFiles(false)
            ->name('*.html')
            ->name('*.xml')
            ->name('*.js')
            ->name('*.jsx')
            ->name('*.css')
            ->name('*.less')
            ->name('*.php')
            ->name('*.phtml')
            ->name('*.ini')
            ->name('*.json')
            ->name('*.txt')
            ->name('*.md')
            ->name('*.yml')
            ->name('*.yaml')
            ->name('.*.yml')
            ->name('.*.yaml')
            ->notName('*.min.*')
            ->exclude('*.Makefile')
            ->exclude('Makefile');

        /** @var \SplFileInfo $file */
        foreach ($finder as $file) {
            $content = openFile($file->getPathname());
            if ($content) {
                isTrue(
                    strpos($content, "\r") === false,
                    'The file contains prohibited symbol "\r" (CARRIAGE RETURN) : ' . $file->getPathname()
                );

                isTrue(
                    strpos($content, "\t") === false,
                    'The file contains prohibited symbol "\t" (TAB) : ' . $file->getPathname()
                );
            }
        }

        isTrue(true); // One assert is a minimum for test complete
    }

    public function testCyrillic(): void
    {
        $finder = (new Finder())
            ->files()
            ->in($this->projectRoot)
            ->exclude($this->excludePaths)
            ->notPath(basename(__FILE__))
            ->ignoreDotFiles(false)
            ->notName('/\.md$/')
            ->notName('/\.min\.(js|css)$/')
            ->notName('/\.min\.(js|css)\.map$/');

        foreach ($finder as $file) {
            $content = openFile($file->getPathname());

            /** @noinspection NotOptimalRegularExpressionsInspection */
            if (preg_match('#[А-Яа-яЁё]#ius', (string)$content)) {
                fail('File contains cyrillic symbols: ' . $file); // Short message in terminal
            }
        }

        isTrue(true); // One assert is a minimum for test complete
    }

    public function testMakefilePhony(): void
    {
        $finderMakefiles = (new Finder())
            ->files()
            ->in($this->projectRoot)
            ->exclude($this->excludePaths)
            ->ignoreDotFiles(false)
            ->name('Makefile')
            ->name('*.Makefile');

        // Collect list for targets
        $makeCommands = [];
        foreach ($finderMakefiles as $file) {
            if ($content = openFile($file->getPathname())) {
                if (preg_match_all('/^([0-9a-z\-\_]*):$/m', $content, $matches)) {
                    foreach (array_keys($matches[0]) as $index) {
                        $makeCommands[] = trim($matches[1][$index]);
                    }
                }
            }
        }

        // Collect list for files
        $finderDirAndFiles = (new Finder())
            ->in($this->projectRoot)
            ->ignoreDotFiles(false)
            ->depth('== 0');

        $rootFilesAndDirs = [];
        foreach ($finderDirAndFiles as $dirOrFile) {
            $rootFilesAndDirs[] = $dirOrFile->getFilename();
        }

        // Get conflict targets
        $mainMakefileContent = openFile(PROJECT_ROOT . '/Makefile');
        if ($mainMakefileContent) {
            $phonyCommands = array_intersect($makeCommands, $rootFilesAndDirs);
            sort($phonyCommands);
            if (count($phonyCommands) > 0) {
                isContain('.PHONY: ' . implode(' ', $phonyCommands) . "\n", $mainMakefileContent);
            } else {
                isNotContain('.PHONY:', $mainMakefileContent);
            }
        }

        isTrue(true); // One assert is a minimum for test complete
    }
}
