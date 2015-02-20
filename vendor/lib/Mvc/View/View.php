<?php

namespace Gwc\Lib\Mvc\View;

class View implements ViewInterface
{
    /**
     * Path to templates base directory (without trailing slash)
     * @var string
     */
    protected $templatesDirectory;

    /**
     * Data available to the view templates
     * @var \Set
     */
    public $data;

    public function __construct()
    {
        $this->data = new Set();
    }

    /**
     * Set the base directory that contains view templates
     * @param   string $directory
     * @throws  \InvalidArgumentException If directory is not a directory
     */
    public function setTemplatesDirectory($directory)
    {
        $this->templatesDirectory = rtrim($directory, DIRECTORY_SEPARATOR);
    }

    /**
     * Get fully qualified path to template file using templates base directory
     * @param  string $file The template file pathname relative to templates base directory
     * @return string
     */
    public function getTemplatePathName($file)
    {
        return $this->templatesDirectory . DIRECTORY_SEPARATOR . ltrim($file, DIRECTORY_SEPARATOR);
    }

    /**
     * Render a template file
     *
     * NOTE: This method should be overridden by custom view subclasses
     *
     * @var    string $template The template pathname, relative to the template base directory
     * @return The rendered template
     * @throws \RuntimeException If resolved template pathname is not a valid file
     */
    public function render($template)
    {
        $templatePathName = $this->getTemplatePathName($template);
        if (!is_file($templatePathName)) {
            throw new \RuntimeException("View cannot render `$template` because the template does not exist");
        }
        extract($this->data->all());
        ob_start();
        require $templatePathName;

        print ob_get_clean();
    }
}
