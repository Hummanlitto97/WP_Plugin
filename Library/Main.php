<?php declare(strict_types=1);

namespace WP_Plugin;
defined('ABSPATH') || exit;

use \WP_Plugin\VirtualPages\{PagesController, Page};
use \WP_Query;
/**
 * Class for WP_Plugin handling
 */
class Main
{
    protected static ?Main $Instance = null;

    public function __construct()
    {
        $this->Predefined_Pages();
        $controller = new PagesController();
        $controller->Init();
        add_filter('do_parse_request', array($controller, 'Dispatch' ), apply_filters("WP_Plugin_priority_parse_request",PHP_INT_MAX), 2);
        add_action('loop_end', 
        function( WP_Query $query ) 
        {
            if(isset( $query->WP_Plugin_Page) && ! empty($query->WP_Plugin_Page)) 
            {
                $query->WP_Plugin_Page = null;
            }
        } );
        add_filter('the_permalink',function(string $plink) 
        {
            global $post,$wp_query;
            if ($wp_query->is_page 
                && isset($wp_query->WP_Plugin_Page)
                && $wp_query->WP_Plugin_Page instanceof Page
                && isset($post->WP_Plugin_Page)) 
                {
                    $plink = home_url( $wp_query->WP_Plugin_Page->GetUrl());
                }
                return $plink;
        });
    } 
    /**
     * Predefined virtual pages
     */
    private function Predefined_Pages() : void
    {
        add_action('WP_Plugin_add_virtual_pages', 
        function(PagesController $controller) 
        {
            $controller->Add_Page(new Page(
                '/custom/page',                 //URL
                'My First Custom Page',         //Title
                Page::VIRTUAL_PAGE_TEMPLATE,    //Template file
                '',                             //Content
                [
                    Page::New_Script("TableControl",WP_Plugin_Resources_URL."Main.js",array(),null,true)
                ],
                [
                    Page::New_Style("TableStyle",WP_Plugin_Resources_URL."Virtual_Pages/Get_Users/CSS/UsersTable.css",array(),null),
                    Page::New_Style("BootstrapTableStyle",WP_Plugin_Resources_URL."CSS/VanillaTable.css",array(),null)
                ]));
        });
    }
    /**
     * Function that initializes plugin instance
     */
    public static function Init() : Main
    {
        is_null( self::$Instance ) && self::$Instance = new self;
        return self::$Instance;
    }
    /**
     * Function that activates plugin
     */
    public static function On_Activation() : void
    {
        if ( ! current_user_can( 'activate_plugins' ) )
        {
            return;
        }
        $plugin = isset( $_REQUEST['plugin'] ) ? $_REQUEST['plugin'] : '';
        check_admin_referer( "activate-plugin_{$plugin}" );
    }
    /**
     * Function that deactivates plugin
     */
    public static function On_Deactivation() : void
    {
        if ( ! current_user_can( 'activate_plugins' ) )
            return;
        $plugin = isset( $_REQUEST['plugin'] ) ? $_REQUEST['plugin'] : '';
        check_admin_referer( "deactivate-plugin_{$plugin}" );
    }
    /**
     * Function that unninstalls plugin
     */
    public static function On_Uninstall() : void
    {
        if ( ! current_user_can( 'activate_plugins' ) )
            return;
        check_admin_referer( 'bulk-plugins' );

        if ( __FILE__ != WP_UNINSTALL_PLUGIN )
            return;
    }
}