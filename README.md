# WP_Plugin - Virtual pages and 1 predefined for task

This plugin lets you create virtual pages with action hook -> WP_Plugin_add_virtual_pages

You can use preinstalled template in plugin -> Pages.php

## License

Inside code file license is void and you should only pay attention to license inside LICENSE file

## Requirements
- PHP 8+
- WordPress 5.6+

## Installation
1. Download these plugin files and place them in Your_Wordpress_Directory\wp-content\plugins
2. Open your WordPress website -> Navigate to plugins section -> Find "WP_Plugin" -> Click "Activate"
3. Once activated, type in URL "https://your_website/custom/page" to check if virtual pages are working (It should open new page which you didn't create)

## Hooks
### Actions
- WP_Plugin_add_virtual_pages - main way to add new virtual pages or do something else
  #### Template
  - WP_Plugin_virtual_page_template_before_content - do something before loading content on Pages.php
  - WP_Plugin_virtual_page_template_after_content - do something after loading content on Pages.php
### Filters
- WP_Plugin_virtual_pages_templates - Before loading virtual page template it is merged into array of backup templates. And this is a way to modify those backup templates as well as original virtual page template
- WP_Plugin_virtual_page_template - After trying to find template for virtual page (Wheter it was successful or not) final decision is on this filter and thus meaning you can modify filtered templates or check if your template will be loaded
- WP_Plugin_virtual_page_additional_check - When user types in URL, request is checked for virtual page and this filter lets you to add additional custom check (It should return bool type)

  #### Template
  - WP_Plugin_virtual_page_template_header - Rewrite header on Pages.php (If not it will load default theme header) 
  (Use anonymous function as return because it is used as callable)
  - WP_Plugin_virtual_page_template_footer - Rewrite footer on Pages.php (If not it will load default theme footer) 
  (Use anonymous function as return because it is used as callable)
## Usage of predefined task page
Click on User data column of ID/Name/Username to open detailed information about him. (If you want to close details just click on any of those columns of displayed user)

## Test Variant: https://github.com/Hummanlitto97/WP_Plugin/tree/Test_Mode
