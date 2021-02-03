<?php declare(strict_types=1);

namespace Tests;

class TestFramework
{
    const FRAMEWORK_ID = "TestFramework";

    public function __construct(public string $Title)
    {
        $this->Test_Environment_Init();
    }
    final protected function Test_Environment_Init()
    {
        global $TestEnvironment;
        $TestEnvironment = array();
        $TestEnvironment[TestFramework::FRAMEWORK_ID] = $this->Title;
    }
    public static function Framework_On()
    {
        global $TestEnvironment;
        return isset($TestEnvironment) && isset($TestEnvironment[TestFramework::FRAMEWORK_ID]);
    }
}