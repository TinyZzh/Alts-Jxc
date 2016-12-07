<?php

namespace Jxc;

final class AutoLoader {

    private $directory;
    private $prefixLength;
    private $prefix;

    private static $includes = array();

    public function __construct($directory = __DIR__) {
        $this->directory = $directory;
        $this->prefix = __NAMESPACE__ . '\\';
        $this->prefixLength = strlen($this->prefix);
    }

    public static function register() {
        spl_autoload_register(array(new self, 'autoload'), true, false);
    }

    public function autoload($clzName) {
        if (isset($clz[$clzName])) {
            return;
        }
        $parts = explode('\\', substr($clzName, $this->prefixLength));
        $filepath = $this->directory . DIRECTORY_SEPARATOR . implode(DIRECTORY_SEPARATOR, $parts) . '.php';
        if (file_exists($filepath)) {
            include_once $filepath;
        }
        self::$includes[$clzName] = 1;
    }
}
