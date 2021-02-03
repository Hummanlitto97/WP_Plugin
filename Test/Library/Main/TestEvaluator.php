<?php declare(strict_types=1);

namespace Tests;

use \Tests\Test;
use \Tests\TestCodes;

class TestEvaluator
{
    const EVALUATOR_ID = TestEvaluator::class;

    public function __construct(private mixed $Result, private array $Trace = [])
    {

    }
    public function Get_Result() : mixed
    {
        return $this->Result;
    }
    public function Get_Trace() : array
    {
        return $this->Trace;
    }
    public function Get_Full_Information() : array
    {
        return array(
            "Result" => $this->Result,
            "Debugtrace" => $this->Trace
        );
    }
    public static function General_Test(mixed $evaluate) : TestEvaluator
    { 
        $trace = debug_backtrace(0)[0];
        is_callable($evaluate) && $evaluate = $evaluate();
        global $TestEnvironment; 
        $TestEnvironment[Test::FOUND_ERROR_ID] = $TestEnvironment[Test::FOUND_ERROR_ID] || !$evaluate;
        return new TestEvaluator($evaluate,$trace);
    }
    public static function Object_Contains_Properties(object $object, array $array) : bool
    {
        if(!isset($object))
        {
            return false;
        }
        foreach($array as $key => $val)
        {
            return isset($object->{$key}) && $object->{$key} === $val;
        }
        return true;
    }
}