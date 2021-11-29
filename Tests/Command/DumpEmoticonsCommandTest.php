<?php

namespace FM\BbcodeBundle\Tests\Command;

use FM\BbcodeBundle\Command\DumpEmoticonsCommand;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Tester\CommandTester;

/**
 * @author Alexandre Quercia <alquerci@email.com>
 */
class DumpEmoticonsCommandTest extends TestCase
{
    private $emoticonFolder;

    private $emoticonPath;

    private $rootDir;

    private $webDir;

    public function setUp(): void
    {
        $this->rootDir = __DIR__ . '/..';
        $this->webDir = sys_get_temp_dir() . '/symfonyFMBbcodeweb';
        if (!file_exists($this->webDir)) {
            mkdir($this->webDir);
        }
        $this->emoticonPath = '/emoticons';
        $this->emoticonFolder = $this->rootDir . '/../vendor/mjohnson/decoda/emoticons';
    }

    public function tearDown(): void
    {
        if (!is_dir($this->webDir)) {
            return;
        }
        $this->removeDirectory($this->webDir);
    }

    public function testExecute(): void
    {
        $command = new DumpEmoticonsCommand($this->webDir, $this->emoticonPath, $this->emoticonFolder);

        $tester = new CommandTester($command);
        $tester->execute([]);

        $this->assertFileExists($this->webDir . $this->emoticonPath);
        $this->assertEquals('Emoticons dumped succesfully' . PHP_EOL, $tester->getDisplay());
    }

    protected function removeDirectory($directory): void
    {
        $iterator = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($directory),
            \RecursiveIteratorIterator::CHILD_FIRST
        );
        foreach ($iterator as $path) {
            if (preg_match('#[/\\\\]\.\.?$#', $path->__toString())) {
                continue;
            }
            if ($path->isDir()) {
                rmdir($path->__toString());
            } else {
                unlink($path->__toString());
            }
        }
        @rmdir($directory);
    }
}
