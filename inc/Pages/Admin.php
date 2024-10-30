<?php 
namespace Miraget\Pages ;
/**
 * @package  Miraget Generator
 */

use \Miraget\Api\SettingsApi ;
use \Miraget\Api\Callbacks\AdminCallbacks ;

class Admin
{

	public $settings ;
	public $callbacks ;
    public $pages = array();
	public $subpages = array();


    public function __construct(){

		$this->settings   = new SettingsApi() ;
		$this->callbacks  =  new AdminCallbacks() ;

		$this->pages = array(

			array(
				'page_title' => 'Miraget Connector',
				'menu_title' => 'Miraget Connector',
				'capability' => 'manage_options',
				'menu_slug'  =>  MIRG_PLUGIN_NAME,
				'callback'   =>  $this->callbacks->calls('admin'),
				'icon_url'   => MIRG_PLUGIN_URL.'assets/image/miragetc.png',
				'position'   => 111
			)

		);
		
		$this->subpages = array(
		
			
			array(
				'parent_slug' => MIRG_PLUGIN_NAME,
				'page_title' => 'Miraget contact',
				'menu_title' => 'Contact Us',
				'capability' => 'manage_options',
				'menu_slug' => 'miraget_contact',
				'callback' => $this->callbacks->calls('emailing')
			)
		);
		
    }
    function register(){

		$this->settings->addPages( $this->pages )
		->withSubPage( 'Admin Panel' ) 
		->addSubPages( $this->subpages )
		->register();

    }


}
