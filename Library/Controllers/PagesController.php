<?php declare(strict_types=1);
namespace WP_Plugin\VirtualPages;

use \WP;
use \WP_Post;
/**
 * Virtual Pages controller
 * @plugin WP_Plugin
 */
class PagesController
{
    /**
     * All Virtual Pages
     */
    private \SplObjectStorage $Pages;
    private ?Page $Matched;

    function __construct() 
    {
        $this->Pages = new \SplObjectStorage;
    }
    /**
     * Executes virtual pages additional actions defined by admin
     */
    function Init() : void
    {
        do_action('WP_Plugin_add_virtual_pages', $this); 
    }

    function Add_Page(Page $page) : Page
    {
        $this->Pages->attach($page);
        return $page;
    }
    /**
     * Intercept with query parsing and add virtual page check
     */
    function Dispatch($bool, WP $wp) : bool
    {
        if ($this->Check_Request() && $this->Matched instanceof Page && apply_filters("WP_Plugin_virtual_page_additional_check",true, $this->Matched)) 
        {
            $this->Matched->Setup_Templates_List();
            $wp->virtual_page = $this->Matched;
            do_action( 'parse_request', $wp);
            $this->Setup_Query();
            do_action('wp', $wp);
            $this->Matched->Load();
            exit();
        }
        return $bool;
    }
    /**
     * Check if virtual page
     */
    private function Check_Request() : bool
    {
        $this->Pages->rewind();
        $path = trim($this->Get_Path_Info(),'/');
        while($this->Pages->valid()) 
        {
            if (trim($this->Pages->current()->Get_Url(),'/') === $path) 
            {
                $this->Matched = $this->Pages->current();
                return true;
            }
            $this->Pages->next();
        }
        return false;
    }        
    /**
     * Get full url of page
     */
    private function Get_Path_Info() : string
    {
        $home_path = parse_url(home_url(), PHP_URL_PATH);
        return preg_replace("#^/?{$home_path}/#", '/',esc_url(add_query_arg(array())));
    }
    /**
     * Ready wp_query with virtual page information
     */
    private function Setup_Query() : void
    {
        global $wp_query;
        $wp_query->init();
        $wp_query->is_page       = true;
        $wp_query->is_singular   = true;
        $wp_query->is_home       = false;
        $wp_query->found_posts   = 1;
        $wp_query->post_count    = 1;
        $wp_query->max_num_Pages = 1;
        $posts = (array) apply_filters(
            'the_posts', array( $this->Matched->To_WpPost()), $wp_query
        );
        $post = $posts[0];
        $wp_query->posts          = $posts;
        $wp_query->post           = $post;
        $wp_query->queried_object = $post;
        $GLOBALS['post']          = $post;
        $wp_query->WP_Plugin_Page = $post instanceof WP_Post && isset($post->WP_Plugin_Page)
            ? $this->Matched
            : NULL;
    }
}