<?php declare(strict_types=1);
namespace Tests\TestWordpress\Classes;

use \Tests\TestWordpress;
use \Tests\Test;
use \Exception;

/**
 * WP_Query representation of WP 
 */
class WP_Query
{
    static function init()
    {
        TestWordpress::Framework_On() || throw new Exception("Wordpress function executed without framework");
        global $TestEnvironment;
        $TestEnvironment[Test::TEST_ID]->Run_General_Test_And_Write(debug_backtrace(0)[0]['function']." called WP_Query init()",1);
    }
}
