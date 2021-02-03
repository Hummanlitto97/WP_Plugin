<?php declare(strict_types=1);

namespace Tests;

use Exception;
use Tests\TestFramework;
use Tests\TestEvaluator;
use Tests\TestWordpress\Classes\WP;

/**
 * Joins all test modules and uses them for test methods
 */
class Test
{
    const TEST_ID = "Test";
    const FOUND_ERROR_ID = "Test_Error";

    /**
     * Initializes Test object with speficified environment
     * @param {\Test\TestIO} $IO Object for writing tests
     * @param {\Test\TestFramework} $framework Object for setting up framework for testinh
     */
    public function __construct(private TestIO $IO = STDIN, private TestFramework $framework)
    {
        $this->Environment_Init();
        register_shutdown_function(function()
        {
            global $TestEnvironment; 
            $TestEnvironment[$this::FOUND_ERROR_ID] && die(1);
        });
        /*set_error_handler(function ($errno,$errstr,$errfile,$errline,$errcontext) : bool 
        {
            var_dump($errno);
            return false;
        });*/
    }
    public function Get_IO()
    {
        return $this->IO;
    }
    protected function Environment_Init()
    {
        global $TestEnvironment, $wp; 
        $wp = new WP();
        $TestEnvironment[Test::TEST_ID] = $this;
        $TestEnvironment[Test::FOUND_ERROR_ID] = false;
        $TestEnvironment["Home"] = "HomeURL";
        $TestEnvironment["LoggedUser"] = "Gandalf";
    }

    public function Run_General_Test_And_Write($message, $evaluated)
    { 
        $info = TestEvaluator::General_Test($evaluated)->Get_Full_Information();
        $this->IO->Stream_Write($this->IO->Format_Evaluation_Response($message,$info["Debugtrace"],$info["Result"]));
    }
}