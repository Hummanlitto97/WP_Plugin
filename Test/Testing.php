<?php declare(strict_types=1);
namespace Tests;
require '../vendor/autoload.php';
require './Library/Environments/TestWordpressGlobalEnvironment.php';
//Test Classes
use \Tests\Test;
use \Tests\TestWordpress;
use \Tests\TestWordpress\Classes\WP;
use \Tests\TestEvaluator;
//Plugin classes
use \WP_Plugin\Main;
use \WP_Plugin\VirtualPages\Page;
use \WP_Plugin\VirtualPages\PagesController;


function Start($w,$t,$c)
{
    $w('<h1 style="text-align:center">TESTS</h1><hr>');
    Test_Class_Main($w,$t,$c);
    Test_Class_Page($w,$t,$c);
    Test_Pages_Controller($w,$t,$c);
    $c();
}
function Test_Class_Page($w,$t,$c)
{
    $w('<h2 style="text-align:center">Testing Class: '.Page::class.'</h2><hr>');
    $t("Page object test succesfull",function() use ($t,$w)
    {
        $scripts = [Page::New_Script("h","a",[],false,true)];
        $styles = [];
        $url = $title = $template = $content = "test";
        $pageTemp = new Page($url,$title,$template,$content,$scripts,$styles);
        $w('<h3>Testing Getters</h3><hr>');
        $t("Get_Url() works",intval($url = $pageTemp->Get_Url() === $url));
        $t("Get_Title() works",intval($title = $pageTemp->Get_Title() === $title));
        $t("Get Template works",intval($template = $pageTemp->Template === $template));
        $t("Get Content works",intval($content = $pageTemp->Content === $content));
        $t("Get Scripts works",intval($scripts = empty(array_udiff($pageTemp->Scripts,$scripts,[],function ($a,$b){$tempA = is_string($a);$tempB = is_string($b); if($tempA || $tempB){if($tempA !== $tempB){return 1;}return $a === $b ? 0 : 1;}; return intval(!($a->Handle === $b->Handle));}))));
        $t("Get Styles works",intval($styles = empty(array_udiff($pageTemp->Styles,$styles,[],function ($a,$b){$tempA = is_string($a);$tempB = is_string($b); if($tempA || $tempB){if($tempA !== $tempB){return 1;}return $a === $b ? 0 : 1;}; return intval(!($a->Handle === $b->Handle));}))));
        $objectCheck = intval($url && $title && $template && $content && $scripts && $styles);
        $t("__contruct() works",$objectCheck);
        $w('<hr><h3>Testing Setters</h3><hr>');
        $temp = $pageTemp->Get_Url();
        $mergeSt = false;
        $mergeSc = false;
        $new = "newTest";
        $t("Set_Title() works",$title = intval($pageTemp->Set_Title($new)->Get_Title() !== $temp && $new === $pageTemp->Get_Title()));
        $temp = $pageTemp->Template;
        $pageTemp->Template = $new;
        $t("Set_Template() works",$template = intval($pageTemp->Template !== $temp && $new === $pageTemp->Template));
        $temp = $pageTemp->Content;
        $pageTemp->Content = $new;
        $t("SetContent() works",$content = intval($pageTemp->Content !== $temp && $new === $pageTemp->Content));
        $new = [Page::New_Script("t","test",[],false,true)];
        $temp = $pageTemp->Scripts;
        $pageTemp->Scripts = $new;
        $t("SetScripts() works",$scripts = intval(count(array_udiff($pageTemp->Scripts,$temp,[],function ($a,$b){$tempA = is_string($a);$tempB = is_string($b); if($tempA || $tempB){if($tempA !== $tempB){return 1;}return $a === $b ? 0 : 1;}; return intval(!($a->Handle === $b->Handle));})) !== 0));
        $new = [Page::New_Style("t","test",[],false)];
        $temp = $pageTemp->Styles;
        $pageTemp->Styles = $new;
        $t("SetStyles() works",$styles = intval(count(array_udiff($pageTemp->Styles,$temp,[],function ($a,$b){$tempA = is_string($a);$tempB = is_string($b); if($tempA || $tempB){if($tempA !== $tempB){return 1;}return $a === $b ? 0 : 1;}; return intval(!($a->Handle === $b->Handle));})) !== 0));
        $new = "newnewTest";
        $temp = $pageTemp->Scripts;
        $t("MergeScripts() works",$mergeSc = intval(count(array_udiff($pageTemp->Merge_Scripts($new)->Scripts,$temp,[],function ($a,$b){$tempA = is_string($a);$tempB = is_string($b); if($tempA || $tempB){if($tempA !== $tempB){return 1;}return $a === $b ? 0 : 1;}; return intval(!($a->Handle === $b->Handle));})) !== 0 && array_search($new,$pageTemp->Scripts,true) !== false));
        $temp = $pageTemp->Styles;
        $t("MergeStyles() works",$mergeSt = intval(count(array_udiff($pageTemp->Merge_Styles($new)->Styles,$temp,[],function ($a,$b){$tempA = is_string($a);$tempB = is_string($b); if($tempA || $tempB){if($tempA !== $tempB){return 1;}return $a === $b ? 0 : 1;}; return intval(!($a->Handle === $b->Handle));})) !== 0 && array_search($new,$pageTemp->Scripts,true) !== false));
        $w('<hr><h3>Testing method DefineResources()</h3><hr>');
        $pageTemp->Define_Resources();
        $w('<hr><h3>Testing method SetupTemplatesList()</h3><hr>');
        $pageTemp->Setup_Templates_List();
        $w('<hr><h3>Testing method Load()</h3><hr>');
        $pageTemp->Load();
        $w('<hr><h3>Testing method ToWpPost()</h3><hr>');
        $pageTemp->To_WpPost();
        return intval($objectCheck && $title && $template && $content && $scripts && $styles && $mergeSc && $mergeSt);
    });
    

    $w('<hr><h3>Testing method New_Script(): Create Script Object </h3><hr>');
    $t("Class 'Page' static method creates std object (As Script or null)",function() use ($t)
    {
        $handle = "Test";
        $src = "Test_Path";
        $deps = array("Dep0");
        $ver = false;
        $inFooter = false;
        $std = Page::New_Script($handle,$src,$deps,$ver,$inFooter);
        $firstTest = TestEvaluator::Object_Contains_Properties($std,array(
            "Handle" => $handle,
            "Src" => $src,
            "Deps" => $deps,
            "Ver" => $ver,
            "InFooter" => $inFooter
        ));
        $t("Method New_Script() created object with all correctly set properties",intval($firstTest));

        $std = Page::New_Script("",$src,$deps,$ver,$inFooter);
        $secondTest = $std === null;
        $t("Method New_Script() created returned null because incorrect parameter given",intval($firstTest));
        return intval($firstTest && $secondTest);
    });
    $w('<h3>Testing method NewStyle(): Create Style Object </h3><hr>');
    $t("Class 'Page' static method creates std object (As Style or null)",function() use ($t)
    {
        $handle = "Test";
        $src = "Test_Path";
        $deps = array("Dep0");
        $ver = false;
        $media = "all";
        $std = Page::New_Style($handle,$src,$deps,$ver,$media);
        $firstTest = TestEvaluator::Object_Contains_Properties($std,array(
            "Handle" => $handle,
            "Src" => $src,
            "Deps" => $deps,
            "Ver" => $ver,
            "Media" => $media
        ));
        $t("Method NewStyle() created object with all correctly set properties",intval($firstTest));

        $std = Page::New_Style("",$src,$deps,$ver,$media);
        $secondTest = $std === null;
        $t("Method NewStyle() created returned null because incorrect parameter given",intval($firstTest));
        return intval($firstTest && $secondTest);
    });
}
function Test_Class_Main($w,$t,$c)
{
    $w('<h2 style="text-align:center">'.Main::class.'</h2><hr>');
    $w('<h3>Testing Init() - _construct() - Predefined_Pages()</h3><hr>');
    $t("Invokes Init() and creates object 'Main' which invokes _construct() -> invokes method Predefined_Pages()", intval(Main::Init() instanceof Main));
    $t("Predefined_Pages() successfully invoked", intval(do_action("WP_Plugin_add_virtual_pages")));
    $w('<hr><h3>Testing Plugin activation</h3><hr>');
    $t("Activation of plugin succesfull", function() {Main::On_Activation();return 1;});
    $w('<hr><h3>Testing Plugin deactivation</h3><hr>');
    $t("Deactivation of plugin succesfull", function() {Main::On_Deactivation();return 1;});
    $w('<hr><h3>Testing Plugin unninstallation</h3><hr>');
    $t("Uninstallation of plugin succesfull", function() {Main::On_Uninstall();return 1;});
}
function Test_Pages_Controller($w,$t,$c)
{
    $w('<hr><h2 style="text-align:center">'.PagesController::class.'</h2><hr>');
    $w('<h3>Testing _construct()</h3><hr>');
    $pageController = new PagesController();
    $t("PagesController _construct() works", intval($pageController instanceof PagesController));
    $w('<hr><h3>Testing Add_Page()</h3><hr>');
    $t("Add_Page() added Page and returned it", intval($pageController->Add_Page(new Page("Test")) instanceof Page));
    $w('<h3>Testing Dispatch()</h3><hr>');
    $pageController->Dispatch(false, new WP());
}
$Test = new Test(new TestIOHTML(fopen("./Test.html","w")),new TestWordpress());
Start(
    function($text) use ($Test){ $Test->Get_IO()->Stream_Write($text);},
    function($message,$evaluated) use ($Test){ $Test->Run_General_Test_And_Write($message,$evaluated);},
    function() use ($Test){ $Test->Get_IO()->Stream_Close();});