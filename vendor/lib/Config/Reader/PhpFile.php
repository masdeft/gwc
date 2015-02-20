<?php

namespace Gwc\Lib\Config\Reader;

/**
 * Description of PhpFile
 *
 * @author
 * @category
 * @copyright Copyright (c) 2013, RingCentral, Inc (http://www.ringcentral.com)
 *
 * @version $Id:$
 */
class PhpFile implements ReaderInterface
{
    public function fromFile($filename)
    {
        if (!is_file($filename) || !is_readable($filename)) {
            throw new \Exception("File '{$filename}' doesn't exist or not readable");
        }

        return file_get_contents($filename);
    }
}
