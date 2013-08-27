<?php

spl_autoload_register('System_Thread_autoload');

function System_Thread_autoload($className)
{
    $prefix = 'System_';
    $path   = sprintf('%s/lib/%s.php', dirname(__FILE__), str_replace('_', '/', $className));
    if ((substr($className, 0, strlen($prefix)) === $prefix) && is_file($path) === true) {
		@include_once $path;
    }

    $prefix = 'Event_';
    $path   = sprintf('%s/lib/Event.php', dirname(__FILE__));
    if ((substr($className, 0, strlen($prefix)) === $prefix) && is_file($path) === true) {
		@include_once $path;
    }

}//end autoload()

ini_set('include_path', ini_get('include_path').PATH_SEPARATOR.dirname(__FILE__).'/lib');

?>