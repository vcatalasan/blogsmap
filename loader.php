<?php
/*
Plugin Name: Blogs Map
Plugin URI: http://www.bscmanage.com/my-plugin/
Description: A plugin to generate subdomains mapping for use with NGINX
Version: 0.0.1
Requires at least: WordPress 2.9.1 / Formidable Pro
Tested up to: WordPress 2.9.1 / BuddyPress 1.2
License: GNU/GPL 2
Author: Val Catalasan
Author URI: http://www.bscmanage.com/staff-profiles/
*/

require( plugin_dir_path( __FILE__) . 'plugin.php' );

// initialize plugin
add_action( 'init', array( 'BlogsMapPlugin', 'get_instance') );

$blogs_map_plugin = BlogsMapPlugin::get_instance( __FILE__ );
