<?php
/**
 * This makes our life easier when dealing with paths. Everything is relative
 * to the application root now.
 */
chdir(dirname(__DIR__));

// Decline static file requests back to the PHP built-in webserver
if (php_sapi_name() === 'cli-server') {
    $path = realpath(__DIR__ . parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
    if (__FILE__ !== $path && is_file($path)) {
        return false;
    }
    unset($path);
}

// include_pathに本体までのパスをセットする
$paths = array(
    'C:\xampp\htdocs\Zend2\library',
    '.',
);
set_include_path(implode(PATH_SEPARATOR, $paths));
 
// ライブラリ本体へのパスを指定
$path = realpath('C:\xampp\htdocs\Zend2\library');
 
// 環境変数を追加
putenv('ZF2_PATH='.$path);

// Setup autoloading
require 'init_autoloader.php';

// Run the application!
Zend\Mvc\Application::init(require 'config/application.config.php')->run();
