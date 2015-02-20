<?php

namespace Gwc\Lib\Config\Writer;

class PhpArray implements WriterInterface
{

    /**
     * Write a config object to a file.
     *
     * @param  string  $filename
     * @param  mixed   $config
     * @return void
     * @throws Exception
     */
    public function toFile($filename, $config)
    {
        if (!is_object($config) && !is_array($config)) {
            throw new Exception(__METHOD__." \$config should be an array");
        }


    }

    /**
     * Write a config object to a string.
     *
     * @param  mixed $config
     * @return string
     * @throws Exception
     */
    public function toString($config)
    {
        if (!is_array($config)) {
            throw new Exception(__METHOD__ . ' expects an array or Traversable config');
        }

        return $this->processConfig($config);
    }

}
