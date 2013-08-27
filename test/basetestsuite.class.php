<?php

/**
 * BaseTestSuite
 *
 * PHP version 5
 *
 * @category   Test
 * @package    Test
 * @author     Rodrigo Garcia <rodrigo@rgnu.com.ar>
 * @license    http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @link       http://www.rgnu.com.ar/
 */

/**
 * BaseTestSuite
 *
 * @category   Test
 * @package    Test
 * @author     Rodrigo Garcia <rodrigo@rgnu.com.ar>
 * @license    http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @link       http://www.rgnu.com.ar/
 */
class BaseTestSuite extends PHPUnit_Framework_TestSuite
{
    /**
     *
     * @return self 
     */
    public static function suite($opts)
    {    
        $defaultOpts = array(
            'path'   => 'test/',
            'filter' =>  '/\/.+test(.class)*\.php$/i',
            'name'   => 'All Test'
        );
        
        $opts = (object) array_merge($defaultOpts, $opts);
        
        $it = new RegexIterator(
            new RecursiveIteratorIterator(
                new RecursiveDirectoryIterator(
                    $opts->path
                )
            ),
            $opts->filter
        );

        $suite = new self($opts->name);
        $suite->addTestFiles($it);

        return $suite;
    }  
}

