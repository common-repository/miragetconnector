<?php
/*
Plugin Name: MiragetConnector(DEPRECATED)
Plugin URI: https://www.miraget.com/
Description: DEPRECATED, MiragetConnector is the only available Wordpress plugin for real-time cloud data synchronization and integration between 1,000+ supported wordpress plugins with platforms such as SaaS software, CRM, Databases, Cloud Apps and more.
Version: 2.2.6
Author: Miraget
Author URI:  https://miraget.com
*/
defined( 'ABSPATH' ) or die( 'U Can\'t include this file with way ' ) ;
/**
 * include autoload file
 */
if( file_exists( dirname( __FILE__ ) . '/vendor/autoload.php' ) ){

    require_once dirname( __FILE__ ) . '/vendor/autoload.php' ;

}else die('Error Loading') ;
/**
 * define ower plugin path
 */
define( 'MIRG_PLUGIN_PATH', plugin_dir_path( __FILE__ ) ) ;
/**
 * define ower plugin path
 */
define( 'MIRG_PLUGIN_URL', plugin_dir_url( __FILE__ ) ) ;
/**
 * define ower plugin base_name
 */
define( 'MIRG_PLUGIN_BN', plugin_basename( __FILE__ ) ) ;
/**
 * define ower plugin base_name
 */
define( 'MIRG_PLUGIN_NAME', 'MiragetConnector_crm_plugin' ) ;
/**
 * define table option
 */
define( 'MIRG_TABLE_OP', 'miragetCon_options' ) ;
/**
 * define table activity
 */
define( 'MIRG_TABLE_ACT', 'miragetCon_acts' ) ;
/**
 * define table activity
 */
define('MIRG_TABLE_EMLS','miragetgen_em_m');

define( 'MIRG_TABLE_DEBG', 'debugg' ) ;
/**
 * define Api url token
 */
define( 'MIRG_API_URL', 'https://token.api.miraget.com/' ) ;
/**
 * The code that runs during plugin activation
 */
function activate_miraget_plugin() {

	 Miraget\Base\Activate::activate();
     $activate = new Miraget\Base\Activate() ;
     $activate->install() ;

}
register_activation_hook( __FILE__, 'activate_miraget_plugin' );
/**
 * The code that runs during plugin deactivation
 */
function deactivate_miraget_plugin() {
	\Miraget\Base\Deactivate::deactivate();
}
register_deactivation_hook( __FILE__ , 'uninstall_miraget_plugin' );
/**
 * Un install pugin
 */
function uninstall_miraget_plugin() {
	\Miraget\Base\Uninstall::uninstall();
}
register_uninstall_hook( __FILE__ , 'uninstall_miraget_plugin' );
/**
 * Initialize && loading All files ( pages action script style .... )
 */
if( class_exists( '\\Miraget\\Init' ) ){
    \Miraget\Init::register_services() ;
}

function purge_history(){
       

     //if(isset( $_POST['purge-history'] ) ){
         global $wpdb;
         $table = $wpdb->prefix. MIRG_TABLE_ACT ;
         $delete = $wpdb->query("TRUNCATE TABLE $table");

         wp_redirect(admin_url() .'admin.php?page=MiragetConnector_crm_plugin');
     //}
 }
add_action('admin_post_purge-history',  'purge_history' );

