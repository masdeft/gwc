<?php

namespace Gwc\Lib\Loader;

require_once 'LoaderInterface.php';

class Loader implements LoaderInterface
{
    const NS_SEPARATOR      = '\\';
    const PREFIX_SEPARATOR  = '_';

    /**
     * All the dirs without their params
     *
     * @var array
     * array('vendor' => './vendor/')
     */
    protected $registeredDirs;

    /**
     * A main bootstrap class
     *
     * @var string
     */
    protected $bootstrap = null;

    /**
     * Defined by Autoloadable; autoload a class.
     *
     * @param  string $className
     * @return false|string
     */
    public function autoload($className)
    {
        $fileName = $this->transformClassNameToFilename($className);
        foreach ($this->registeredDirs as $dirParams) {
            $namespace = str_replace(self::NS_SEPARATOR, '/', $dirParams['namespace']);
            if (strpos($fileName, $namespace) !== false) {
                $fileName = str_replace($namespace, '', $fileName);
                $classFile = rtrim($dirParams['dir'], '/') . DIRECTORY_SEPARATOR . ltrim($fileName, '/');
                if (file_exists($classFile)) {
                    return include ($classFile);
                }
            }
        }
        return false;
    }

    /**
     * Transform the class name to a filename
     *
     * @param  string $className
     * @return string
     */
    public function transformClassNameToFilename($className)
    {
        // $class may contain a namespace portion, in  which case we need
        // to preserve any underscores in that portion.
        $matches = array();
        preg_match('/^(?P<app>[^\\\]+)?(?P<namespace>.+\\\)?(?P<class>[^\\\]+$)/', $className, $matches);

        $className = (isset($matches['class'])) ? $matches['class'] : '';
        $namespace = (isset($matches['namespace'])) ? $matches['namespace'] : '';
        $application = (isset($matches['app'])) ? $matches['app'] : '';

        return $application
               . str_replace(self::NS_SEPARATOR, '/', $namespace)
               . str_replace(self::PREFIX_SEPARATOR, '/', $className)
               . '.php';
    }

    /**
     * Register the autoloader with spl_autoload
     *
     * @param array $dirsForRegistration
     * @throws \Exception
     * @return void
     */
    public function register(array $dirsForRegistration)
    {
        foreach ($dirsForRegistration as $name => $dirParams) {
            if (is_array($dirParams)) {
                if (!array_key_exists('dir', $dirParams)) {
                    throw new \Exception('It has to be the key named "dir"');
                }
                $this->registeredDirs[$name] = $dirParams;
            } else {
                throw new \Exception('Dir value has to be an array with params');
            }
        }
        return spl_autoload_register(array($this, 'autoload'));
    }

    /**
     * Get all the registered dirs
     *
     * @return array
     */
    public function getRegisteredDirs()
    {
        return $this->registeredDirs;
    }

    /**
     * @param string $bootstrap
     */
    public function setBootstrap($bootstrap)
    {
        $this->bootstrap = $bootstrap;
    }

    /**
     * @return string
     */
    public function getBootstrap()
    {
        return $this->bootstrap;
    }
}
