<?php declare(strict_types=1);
namespace Tests\TestWordpress\Classes;

use \Tests\TestWordpress;
use \Tests\Test;
use \Exception;

/**
 * WP_Post representation of WP 
 */
class WP_Post
{
    public int $ID;
    public string $post_title = '';
    public string $post_name = '';
    public string $post_content = '';
    public string $post_excerpt = '';
    public int $post_parent = 0;
    public int $menu_order = 0;
    public string $post_type = 'post';
    public string $post_mime_type = 'post';
    public string $post_status = 'publish';
    public string $comment_status = 'open';
    public string $ping_status = 'open';
    public int $comment_count = 0;
    public string $post_password = '';
    public string $to_ping = '';
    public string $filter = '';
    public string $pinged = '';
    public string $guid = '';
    public string $post_modified = '0000-00-00 00:00:00';
    public string $post_modified_gmt = '0000-00-00 00:00:00';
    public string $post_content_filtered = '0000-00-00 00:00:00';
    public string $post_date = '0000-00-00 00:00:00';
    public string $post_date_gmt = '0000-00-00 00:00:00';
    public string $post_author = '0';

    public function __construct(object $post)
    {
        TestWordpress::Framework_On() || throw new Exception("Wordpress function executed without framework");
        global $TestEnvironment;
        if(is_object($post))
        {
            foreach(get_object_vars($post) as $key => $val)
            {
                $this->$key = $val;
            }
        }   
        $TestEnvironment[Test::TEST_ID]->Run_General_Test_And_Write(debug_backtrace(0)[0]['function']." called WP_Post __construct()",1);
    }
}
