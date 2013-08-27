<?php

//require_once 'bootstrap.inc.php';

/**
 * All Unit Test
 *
 * Ejecuta todos los test que se encuentran dentro
 * de la carpeta unit y que contenga el sufijo
 * test.php
 *
 */
class AllUnitTest extends BaseTestSuite
{
    public static function suite()
    {
        return parent::suite(
            array(
                'name' => 'All Unit Test', 
                'path' => implode(
                    DIRECTORY_SEPARATOR, 
                    array(dirname(__FILE__), 'unit')
                )
            )
        );
    }
}
