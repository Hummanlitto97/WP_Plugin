<?php declare(strict_types=1);
use \Tests\TestWordpress;
use \Tests\Test;

define("WP_Plugin_Root","G:\Xamp-PHP8\htdocs\wordpress\wp-content\plugins\WP-Plugin");
define("WP_UNINSTALL_PLUGIN",false);

function do_action(string $tag, ...$arg)
{
    TestWordpress::Framework_On() || throw new Exception("Wordpress function executed without framework");
    global $TestEnvironment;
    if(isset($TestEnvironment[TestWordpress::ACTIONS_ID][$tag]))
    {
        $action = $TestEnvironment[TestWordpress::ACTIONS_ID][$tag]; 
        //$action "function" => $function_to_add, "priority" => $priority, "args" => $accepted_args));
        $TestEnvironment[Test::TEST_ID]->Run_General_Test_And_Write($tag." action was executed.", 1);
        return true;
    }
    $TestEnvironment[Test::TEST_ID]->Run_General_Test_And_Write($tag." action was called but it is not declared.", 2);
    return false;

}
function add_action($tag, $function_to_add, $priority = 10, $accepted_args = 1 )
{
    TestWordpress::Framework_On() || throw new Exception("Wordpress function executed without framework");
    global $TestEnvironment;
    if(isset($TestEnvironment[TestWordpress::ACTIONS_ID][$tag]))
    {
        $TestEnvironment[Test::TEST_ID]->Run_General_Test_And_Write($tag." action was already declared. (Repetition)", 0);
        return false;
    }
    $TestEnvironment[TestWordpress::ACTIONS_ID][$tag] = array("function" => $function_to_add, "priority" => $priority, "args" => $accepted_args);
    $TestEnvironment[Test::TEST_ID]->Run_General_Test_And_Write($tag." action was added to actions list.", 1);
    return true;
}
function add_filter($tag, $function_to_add, $priority = 10, $accepted_args = 1)
{
    TestWordpress::Framework_On() || throw new Exception("Wordpress function executed without framework");
    global $TestEnvironment;
    if(isset($TestEnvironment[TestWordpress::FILTERS_ID][$tag]))
    {
        $TestEnvironment[Test::TEST_ID]->Run_General_Test_And_Write($tag." filter was already declared. (Repetition)", 0);
        return;
    }
    $TestEnvironment[TestWordpress::FILTERS_ID][$tag] = array("function" => $function_to_add, "priority" => $priority, "args" => $accepted_args);
    $TestEnvironment[Test::TEST_ID]->Run_General_Test_And_Write($tag." filter was added to filters list.", 1);
}
function apply_filters(string $tag, mixed $value)
{
    TestWordpress::Framework_On() || throw new Exception("Wordpress function executed without framework");
    global $TestEnvironment;
    if(isset($TestEnvironment[TestWordpress::ACTIONS_ID][$tag]))
    {
        $filter = $TestEnvironment[TestWordpress::ACTIONS_ID][$tag]; 
        $TestEnvironment[Test::TEST_ID]->Run_General_Test_And_Write($tag." filter was executed.", 1);
        return $value;
    }
    $TestEnvironment[Test::TEST_ID]->Run_General_Test_And_Write($tag." filter was called but it is not declared.", 2);
    return $value;
}
function wp_enqueue_script(string $handle, string $src = '', array $deps = array(), string|bool|null $ver = false, bool $in_footer = false )
{
    TestWordpress::Framework_On() || throw new Exception("Wordpress function executed without framework");
    global $TestEnvironment;
    if(isset($TestEnvironment[TestWordpress::SCRIPTS_ID][$handle]))
    {
        $TestEnvironment[Test::TEST_ID]->Run_General_Test_And_Write($handle." script was already declared. (Repetition)", 0);
        return;
    }
    $TestEnvironment[TestWordpress::SCRIPTS_ID][$handle] = array("src" => $src, "dependencies" => $deps, "version" => $ver, "in_footer" => $in_footer);
    $TestEnvironment[Test::TEST_ID]->Run_General_Test_And_Write($handle." script was added to scripts list.", 1);  
}
function wp_enqueue_style(string $handle, string $src = '', array $deps = array(), string|bool|null $ver = false, string $media = 'all' )
{
    TestWordpress::Framework_On() || throw new Exception("Wordpress function executed without framework");
    global $TestEnvironment;
    if(isset($TestEnvironment[TestWordpress::STYLES_ID][$handle]))
    {
        $TestEnvironment[Test::TEST_ID]->Run_General_Test_And_Write($handle." style was already declared. (Repetition)", 0);
        return;
    }
    $TestEnvironment[TestWordpress::STYLES_ID][$handle] = array("src" => $src, "dependencies" => $deps, "version" => $ver, "media" => $media);
    $TestEnvironment[Test::TEST_ID]->Run_General_Test_And_Write($handle." style was added to styles list.", 1);  
}
function sanitize_title(string $text)
{
    TestWordpress::Framework_On() || throw new Exception("Wordpress function executed without framework");
    global $TestEnvironment;
    $TestEnvironment[Test::TEST_ID]->Run_General_Test_And_Write(debug_backtrace(0)[0]['function']." sanitazed title: ".$text,1);
    return $text;
}
function home_url(string $path = '', string|null $scheme = null)
{
    TestWordpress::Framework_On() || throw new Exception("Wordpress function executed without framework");
    global $TestEnvironment;
    $newUrl = $TestEnvironment["Home"]."/".$path;
    $TestEnvironment[Test::TEST_ID]->Run_General_Test_And_Write(debug_backtrace(0)[0]['function']." calling to add home url: ".$newUrl,1);
    return $newUrl;
}
function current_time(string $type)
{
    TestWordpress::Framework_On() || throw new Exception("Wordpress function executed without framework");
    global $TestEnvironment;
    $TestEnvironment[Test::TEST_ID]->Run_General_Test_And_Write(debug_backtrace(0)[0]['function']." calling to get time from ".$type,1);
    return (new DateTime())->format("Y-m-d H:i:s");
}
function is_user_logged_in()
{
    TestWordpress::Framework_On() || throw new Exception("Wordpress function executed without framework");
    global $TestEnvironment;
    $logged = true;
    if(empty($TestEnvironment["LoggedUser"]))
    {
        $TestEnvironment[Test::TEST_ID]->Run_General_Test_And_Write(debug_backtrace(0)[0]['function']." calling to get logged in user and it is empty. No one logged in.",1);
        $logged = false;
    }
    else
    {
        $TestEnvironment[Test::TEST_ID]->Run_General_Test_And_Write(debug_backtrace(0)[0]['function']." calling to get logged in user : ".$TestEnvironment["LoggedUser"],1);
        $logged = $TestEnvironment["LoggedUser"];
    }
    return $logged;
}
function get_current_user_id()
{
    TestWordpress::Framework_On() || throw new Exception("Wordpress function executed without framework");
    global $TestEnvironment;
    if(empty($TestEnvironment["LoggedUser"]))
    {
        $TestEnvironment[Test::TEST_ID]->Run_General_Test_And_Write(debug_backtrace(0)[0]['function']." calling to get current user id but is empty. Forgot to login ?",0);
        $logged = false;
    }
    else
    {
        $TestEnvironment[Test::TEST_ID]->Run_General_Test_And_Write(debug_backtrace(0)[0]['function']." calling to get logged user id: ".$TestEnvironment["LoggedUser"],1);
        $logged = $TestEnvironment["LoggedUser"];
    }
    return $logged;
}
function current_user_can(string $capability, mixed ...$args ) : bool
{
    TestWordpress::Framework_On() || throw new Exception("Wordpress function executed without framework");
    global $TestEnvironment;
    $TestEnvironment[Test::TEST_ID]->Run_General_Test_And_Write("current_user_can() called and checking for capability of: ".$capability,1);
    return true;
}
function locate_template(string|array $template_names, bool $load = false, bool $require_once = true, array $args = array() )
{
    TestWordpress::Framework_On() || throw new Exception("Wordpress function executed without framework");
    global $TestEnvironment;
    if(!is_array($template_names))
    {
        $TestEnvironment[Test::TEST_ID]->Run_General_Test_And_Write("locate_template() didn't get a templates list",0);
    }    
    if(empty($template_names))
    {
        $TestEnvironment[Test::TEST_ID]->Run_General_Test_And_Write("locate_template() got an empty templates list",0);
    }
    foreach($template_names as $val)
    {
        $TestEnvironment[Test::TEST_ID]->Run_General_Test_And_Write("Template in the list: ".$val,1);
    }
    return $template_names[0];
}
function check_admin_referer(int|string $action = -1, string $query_arg = '_wpnonce')
{
    TestWordpress::Framework_On() || throw new Exception("Wordpress function executed without framework");
    global $TestEnvironment;
    $TestEnvironment[Test::TEST_ID]->Run_General_Test_And_Write("check_admin_referer() checking if user intention and got action: ".$action,1);
    return 1;
}
function esc_url( $url, $protocols = null, $_context = 'display' ) 
{
    TestWordpress::Framework_On() || throw new Exception("Wordpress function executed without framework");
    global $TestEnvironment;
    $TestEnvironment[Test::TEST_ID]->Run_General_Test_And_Write(debug_backtrace(0)[0]['function']." calling esc_url() to clean url: ".$url,1);
    return $url;
}
function add_query_arg(...$args) 
{
    TestWordpress::Framework_On() || throw new Exception("Wordpress function executed without framework");
    global $TestEnvironment;
    $TestEnvironment[Test::TEST_ID]->Run_General_Test_And_Write(debug_backtrace(0)[0]['function']." calling add_query_arg() to create url",1);
    return "https://added";
}
