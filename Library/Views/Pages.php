<?php declare(strict_types=1);
namespace WP_Plugin\VirtualPages;
/**
 * View for virtual pages
 * @plugin WP_Plugin
 */
$current_post = get_post();

$header = apply_filters("WP_Plugin_virtual_page_template_header",function() {wp_head();});
$footer = apply_filters("WP_Plugin_virtual_page_template_footer",function() {wp_footer();});
$header();

do_action("WP_Plugin_virtual_page_template_before_content"); 
?>

<div id="site-content" role="main">
    <?php  echo $current_post->post_content?>
</div>
<?php 

do_action("WP_Plugin_virtual_page_template_after_content"); 
$footer();

?>