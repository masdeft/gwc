<?php

namespace Gwc\Lib\Config\Writer;

use Gwc\Lib\Config\Reader\PhpFile as PhpFileReader;

class PhpFile implements WriterInterface
{

    /**
     * Write a config object to a file.
     *
     * @param  string  $filename
     * @param  mixed   $config
     * @return void
     */
    public function toFile($filename, $config)
    {
        return file_put_contents($filename, $config);
    }

    /**
     * Write a config object to a string.
     *
     * @param  mixed $config
     * @return string
     */
    public function toString($config)
    {
        // TODO: Implement toString() method.
    }

}
