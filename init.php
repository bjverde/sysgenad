<?php
if (version_compare(PHP_VERSION, '7.1.0') == -1)
{
    die ('The minimum version required for PHP is 7.1.0');
}

// define the autoloader
require_once 'lib/adianti/core/AdiantiCoreLoader.php';
spl_autoload_register(array('Adianti\Core\AdiantiCoreLoader', 'autoload'));
Adianti\Core\AdiantiCoreLoader::loadClassMap();

$loader = require 'vendor/autoload.php';
$loader->register();

// read configurations
$ini = parse_ini_file('app/config/application.ini', true);
date_default_timezone_set($ini['general']['timezone']);
AdiantiCoreTranslator::setLanguage( $ini['general']['language'] );
ApplicationTranslator::setLanguage( $ini['general']['language'] );
AdiantiApplicationConfig::load($ini);
AdiantiApplicationConfig::apply();

// define constants
define('APPLICATION_NAME', $ini['general']['application']);
define('OS', strtoupper(substr(PHP_OS, 0, 3)));
define('PATH', dirname(__FILE__));
define('LANG', $ini['general']['language']);

// ============= SysGen For Adianti  =================//
define('FORMDIN_VERSION', $ini['system']['formdin_min_version']);
define('SYSTEM_VERSION', $ini['system']['version']);

define('ROOT_PATH', '../');
if(!defined('ROWS_PER_PAGE') ) { 
    define('ROWS_PER_PAGE', 20); 
}
if(!defined('ENCODINGS') ) { 
    define('ENCODINGS', 'UTF-8'); 
}
if(!defined('DS')) { 
    define('DS', DIRECTORY_SEPARATOR); 
}
if (!defined('EOL')) {
    define('EOL', "\n");
}
if (!defined('ESP')) {
    $esp = chr(32).chr(32).chr(32).chr(32);
    //define('ESP', '    ');
    define('ESP', $esp);
}
if (!defined('TAB')) {
    define('TAB', chr(9));
}

// ============================================//

// custom session name
session_name('PHPSESSID_'.$ini['general']['application']);

if (version_compare(PHP_VERSION, '7.0.0') == -1)
{
    die(AdiantiCoreTranslator::translate('The minimum version required for PHP is ^1', '7.0.0'));
}
