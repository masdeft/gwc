<?php

namespace Gwc\Lib\Loader;

interface LoaderInterface
{
    /**
     * @param $className
     * @return mixed
     */
    public function autoload($className);

    /**
     * @param $className
     * @return mixed
     */
    public function transformClassNameToFilename($className);

    /**
     * @param array $dirsForRegistration
     * @return mixed
     */
    public function register(array $dirsForRegistration);
}
