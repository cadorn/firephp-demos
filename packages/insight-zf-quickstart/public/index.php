<?php

// Define path to application directory
defined('APPLICATION_PATH')
    || define('APPLICATION_PATH', realpath(dirname(__FILE__) . '/../application'));

// Define application environment
defined('APPLICATION_ENV')
    || define('APPLICATION_ENV', (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : 'production'));

// Ensure library/ is on include_path
set_include_path(implode(PATH_SEPARATOR, array(
    realpath(APPLICATION_PATH . '/../../insight-zf-1/library'),
    get_include_path(),
)));

/** Zend_Application */
require_once 'Zend/Application.php';

// Create application, bootstrap, and run
$application = new Zend_Application(
    APPLICATION_ENV,
    APPLICATION_PATH . '/configs/application.ini'
);


$console = FirePHP::to('request')->console('Errors');
$engine = FirePHP::plugin('engine');
$engine->onError($console);
$engine->onAssertionError($console);
$engine->onException($console);

$console = FirePHP::to('request')->console('Bootstrap');
$console->label('Environment')->log($application->getEnvironment());
$console->label('Options')->log($application->getOptions());

$console = FirePHP::to('request')->console('External Variables');
$console->label('Headers')->log(getallheaders());
$console->label('$_GET')->log($_GET);
$console->label('$_POST')->log($_POST);


$application->bootstrap()
            ->run();
