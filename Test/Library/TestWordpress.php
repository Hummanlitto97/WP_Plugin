<?php declare(strict_types=1);

namespace Tests;

use Exception;
use \Tests\TestFramework;

class TestWordpress extends TestFramework
{
    const FRAMEWORK_ID = "Wordpress";
    
    const FILTERS_ID = "Filters";
    const ACTIONS_ID = "Actions";
    const STYLES_ID = "Styles";
    const SCRIPTS_ID = "Scripts";
    public function __construct()
    {
        TestFramework::__construct($this::FRAMEWORK_ID);
        $this->Init();
    }
    protected function Init(): void
    {
        global $TestEnvironment;
        $TestEnvironment[TestWordpress::FILTERS_ID] = array();
        $TestEnvironment[TestWordpress::ACTIONS_ID] = array();
        $TestEnvironment[TestWordpress::STYLES_ID] = array();
        $TestEnvironment[TestWordpress::SCRIPTS_ID] = array();
    }
    public static function Framework_On() : bool
    {
        global $TestEnvironment;
        return isset($TestEnvironment) && isset($TestEnvironment[TestFramework::FRAMEWORK_ID]) && $TestEnvironment[TestFramework::FRAMEWORK_ID] === TestWordpress::FRAMEWORK_ID;
    }
}
