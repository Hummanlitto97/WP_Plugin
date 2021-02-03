<?php declare(strict_types=1);
namespace WP_Plugin\VirtualPages;

use stdClass;
use Tests\TestWordpress\Classes\WP_Post;
/**
 * Virtual page model
 * @plugin WP_Plugin
 */
class Page 
{
    /**
     * Predefined file path
     */
    const VIRTUAL_PAGE_TEMPLATE = WP_Plugin_Root."Library\Views\Pages.php";

    private ?WP_Post $Wp_post = null;
    private ?array $Templates = null;

    function __construct(
        private string $Url, 
        private string $Title = 'Untitled', 
        public string $Template = 'page.php',
        public ?string $Content = null,
        public ?array $Scripts = null,
        public ?array $Styles = null) 
    {
        $this->Set_Url($Url)->Set_Title($Title);
    }

    /**
     * Creates std object for Script which is used in Page loading and in its model
     * @param string $handle Unique name for Script
     * @param string $src Path to resource
     * @param array<int,string> $deps Array of dependencies
     * @param string|bool $ver File version
     * @param bool $inFooter Should script load in footer ?
     * @return stdClass|null
     * stdClass contains these properties (All explained in params):
     * - Handle
     * - Src
     * - Deps
     * - Ver
     * - InFooter
     */
    static function New_Script(string $handle, string $src, array $deps = array(), string|bool|null $ver = false, bool $inFooter = false) : ?stdClass
    {
        if(!empty($handle) && !empty($src))
        {
            $AF_temp = new stdClass();
            $AF_temp->Handle = $handle;
            $AF_temp->Src = $src;
            $AF_temp->Deps = $deps;
            $AF_temp->Ver = $ver;
            $AF_temp->InFooter = $inFooter;
            return $AF_temp;
        }
        return null;
    }

     /**
     * Creates std object for Style which is used in Page loading and in its model
     * @param string $handle Unique name for Style
     * @param string $src Path to resource
     * @param array<int,string> $deps Array of dependencies
     * @param string|bool $ver File version
     * @param string $media file used for specific media conditions
     * @return stdClass|null
     * stdClass contains these properties (All explained in params):
     * - Handle
     * - Src
     * - Deps
     * - Ver
     * - Media
     */
    static function New_Style($handle, string $src, array $deps = array(), string|bool|null $ver = false, string $media = "all") : stdClass | null
    {
        if(!empty($handle) && !empty($src))
        {
            $AF_temp = new stdClass();
            $AF_temp->Handle = $handle;
            $AF_temp->Src = $src;
            $AF_temp->Deps = $deps;
            $AF_temp->Ver = $ver;
            $AF_temp->Media = $media;
            return $AF_temp;
        }
        return null;
    }
    /**
     * Gets url
     */
    function Get_Url() : string | null
    {
        return $this->Url;
    }
    /**
     * Gets title
     */
    function Get_Title() : string
    {
        return $this->Title;
    }
    /**
     * Sanitizes and sets title to new title
     */
    function Set_Title($Title) : Page
    {
        $this->Title = filter_var($Title, FILTER_SANITIZE_STRING);
        return $this;
    }
    /**
     * Merges array of Scripts with given array of $Scripts or script (JavaScript text)    
     */
    function Merge_Scripts(array|string $Scripts) : Page
    {
        if(is_array($Scripts))
        {
            $this->Scripts = array(...$Scripts,...($this->Scripts));
        }
        if(is_string($Scripts))
        {
            $this->Scripts = array($Scripts,...($this->Scripts));
        }
        return $this;
    }
    /**
     * Merges array of Styles with given array of $Styles or style (CSS text)    
     */
    function Merge_Styles(array|string $Styles): Page
    {
        if(is_array($Styles))
        {
            $this->Styles = array(...$Styles,...($this->Styles));
        }
        if(is_string($Styles))
        {
            $this->Styles = array($Styles,...($this->Styles));
        }
        return $this;
    }
    /**
     * Enqueue styles and scripts for virtual page
     */
    function Define_Resources() : void
    {
        for($i = 0;$i < count($this->Scripts);$i++)
        {
            if($this->Scripts[$i] instanceof stdClass)
            {
                wp_enqueue_script($this->Scripts[$i]->Handle,$this->Scripts[$i]->Src,$this->Scripts[$i]->Deps,$this->Scripts[$i]->Ver,$this->Scripts[$i]->InFooter);
            }
        }
        for($i = 0;$i < count($this->Styles);$i++)
        {
            if($this->Styles[$i] instanceof stdClass)
            {
                wp_enqueue_style($this->Styles[$i]->Handle,$this->Styles[$i]->Src,$this->Styles[$i]->Deps,$this->Styles[$i]->Ver,$this->Styles[$i]->Media);
            }
        }
    }

    private function Set_Url() : Page
    {
        $this->Url = filter_var( $this->Url, FILTER_SANITIZE_URL );
        return $this;
    }
    /**
     * Loads all template files for virtual page (With backup files)
     */
    public function Setup_Templates_List() : void
    {
        $this->Templates = apply_filters("WP_Plugin_virtual_pages_templates",[
            ...array('page.php', 'index.php' ), ...(array) $this->Template]);
    }
    /**
     * Load virtual page from template
     */
    public function Load() : void
    {
        do_action('template_redirect');

        $this->Template === self::VIRTUAL_PAGE_TEMPLATE ? $Template = self::VIRTUAL_PAGE_TEMPLATE : $Template = locate_template(array_filter($this->Templates));
        $filtered = apply_filters('template_include', apply_filters('WP_Plugin_virtual_page_template', $Template));
        if(empty($filtered) || file_exists($filtered))
        {
            $Template = $filtered;
        }
        if(!empty($Template) && file_exists($Template)) 
        {
            add_action('wp_enqueue_scripts',function (){$this->Define_Resources();});
            require_once $Template;
        }
    }

    /**
     * Page as WP_Post
     * @return WP_Post
     */
    function To_WpPost() : ?WP_Post
    {
        if(is_null( $this->Wp_post)) 
        {
            $post = array(
                'ID'                   => 0,
                'post_title'           => $this->Title,
                'post_name'            => sanitize_title( $this->Title ),
                'post_content'         => $this->Content ? : '',
                'post_excerpt'         => '',
                'post_parent'          => 0,
                'menu_order'           => 0,
                'post_type'            => 'page',
                'post_status'          => 'publish',
                'comment_status'       => 'closed',
                'ping_status'          => 'closed',
                'comment_count'        => 0,
                'post_password'        => '',
                'to_ping'              => '',
                'pinged'               => '',
                'guid'                 => home_url( $this->Get_Url() ),
                'post_date'            => current_time( 'mysql' ),
                'post_date_gmt'        => current_time( 'mysql', 1 ),
                'post_author'          => is_user_logged_in() ? get_current_user_id() : 0,
                'WP_Plugin_Page'       => $this,
                'filter'               => 'raw'
            );
            $this->Wp_post = new WP_Post((object)$post);
        }
        return $this->Wp_post;
    }
}