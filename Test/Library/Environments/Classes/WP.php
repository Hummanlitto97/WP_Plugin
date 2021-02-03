<?php declare(strict_types=1);
namespace Tests\TestWordpress\Classes;

use \Tests\TestWordpress;
use \Tests\Test;
use \Exception;

/**
 * WP_Query representation of WP 
 */
class WP
{
    function _construct()
    {
        TestWordpress::Framework_On() || throw new Exception("Wordpress function executed without framework");
    }
    static function init()
    {
        TestWordpress::Framework_On() || throw new Exception("Wordpress function executed without framework");
    }
}
