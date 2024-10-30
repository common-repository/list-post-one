<?php
/*
Plugin Name: List Posts
Plugin URI: http://photoboxone.com
Description: List Posts is a plugin show latest posts in widget.
Author: PB One
Author URI: http://photoboxone.com
Version: 1.0.7
License: GPL-2.0+
License URI: http://www.gnu.org/licenses/gpl-2.0.html
*/

defined('ABSPATH') or die;

function listbox_url( $path = '' )
{
	return plugins_url( $path, __FILE__);
}

function listbox_ver()
{
	return '20220114';
}

function listbox_path( $path = '' )
{
	return dirname(__FILE__). ( substr($path,0,1) !== '/' ? '/' : '' ) . $path;
}

function listbox_include( $path_file = '' )
{
	if( $path_file!='' && file_exists( $p = listbox_path('includes/'.$path_file ) ) ) {
		require $p;
		return true;
	}
	return false;
}

listbox_include('widget.php');

if( is_admin() ) {
	
	$pagenow = isset($GLOBALS['pagenow'])?$GLOBALS['pagenow']:'';
	
	if( $pagenow == 'plugins.php' ){
		
		function listbox_plugin_actions( $actions, $plugin_file, $plugin_data, $context ) {
			$url_setting = admin_url('widgets.php');
			
			array_unshift($actions, '<a href="$url_setting">' . __("Widgets") . '</a>' );
			return $actions;
		}
		
		add_filter("plugin_action_links_".plugin_basename(__FILE__), "listbox_plugin_actions", 10, 4);
	}

}