<?php

/*
* (c) Andreas Fischer <bantu@owncloud.com>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/

/**
* Tests "filesystem functions" as defined by
* http://www.php.net/manual/en/ref.filesystem.php
*/
class FilesystemTest extends PHPUnit_Framework_TestCase
{
    protected $file;
    protected $filesize;
    protected $sha256sum;

    public function setUp()
    {
        if (!isset($_SERVER['FILE'])) {
            $this->markTestSkipped(
                'You must provide some environment variables'
            );
        }
        $this->file = $_SERVER['FILE'];
        $this->filesize = $_SERVER['FILESIZE'];
        $this->sha256sum = $_SERVER['SHA256SUM'];
    }

    public function tearDown()
    {
        exec(sprintf(
            'truncate --size %s %s',
            escapeshellarg($this->filesize),
            escapeshellarg($this->file)
        ));
    }

    public function testFilesize()
    {
        $this->assertSame(
            $this->filesize,
            sprintf('%u', filesize($this->file))
        );
    }

    public function testHashFile()
    {
        $this->assertSame($this->sha256sum, hash_file('sha256', $this->file));
    }

    public function testFopenReadFeofFreadFclose()
    {
        $hash = hash_init('sha256');
        $fp = fopen($this->file, 'rb');
        while (!feof($fp)) {
            hash_update($hash, fread($fp, 4096));
        }
        fclose($fp);
        $this->assertSame($this->sha256sum, hash_final($hash));
    }

    /**
    * @depends testHashFile
    * @depends testFopenReadFeofFreadFclose
    */
    public function testFopenAppendFwriteFclose()
    {
        $data = str_repeat('ABC', 1024 * 1024);

        $hash = $this->getOpenHash($this->file);
        hash_update($hash, $data);
        $expected = hash_final($hash);

        $fp = fopen($this->file, 'ab');
        fwrite($fp, $data);
        fclose($fp);

        $this->assertSame($expected, hash_file('sha256', $this->file));
    }

    protected function getOpenHash($filename, $algo = 'sha256')
    {
        $hash = hash_init($algo);
        $fp = fopen($this->file, 'rb');
        while (!feof($fp)) {
            hash_update($hash, fread($fp, 8192));
        }
        fclose($fp);
        return $hash;
    }
}
