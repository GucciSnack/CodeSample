<?php
/**
 * CONTROLLERS
 *
 * The following three instances of spl_autoload_register() declare functions that allow the autoloading of classes
 * and traits based on the following naming schemes:
 *    public/classes/<namespace>/class.<classname>.php (all lowercase)
 *    public/classes/class.<classname>.php
 *    public/classes/<namespace>/trait.<traitname>.php (all lowercase)
 */
spl_autoload_register(
    function ($className) {
        $className = ltrim($className, '\\/');

        if (DIRECTORY_SEPARATOR === '/')
        {
            // unix, linux, mac (windows operates using '\' as a directory separator)
            $className = str_replace('\\', '/', $className);
        }

        $pathName = dirname(__DIR__) . DIRECTORY_SEPARATOR
            . lcfirst($className) . '.php';

        if (file_exists($pathName) === true) {
            include_once $pathName;
            return true;
        }

        return false;
    }
);