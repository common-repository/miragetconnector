<?php namespace Miraget\Pages ;
/**
 * @package  Miraget Generator
 * inser emairs if exist
 */ 
/* 
use \Miraget\Api\SettingsApi ;
use \Miraget\Api\Callbacks\AdminCallbacks ; */

class AddEmailes 
{ 
    private $DB ;

    function __construct(){

        /* Global  */
        global $wpdb;
        // init database
        $this->DB = $wpdb ;
    }
    public function register(){
       
         
       // add_action( 'wp_footer',  array( $this, 'ajaxEmales' ) ); 
        add_action( 'wp_ajax_nopriv_mboim_ajax_add_text', array( $this, 'ajaxEmales' ) );
 
    }
    public function ajaxEmales(){
        
      if( isset( $_POST['text'] )   ){
        
        $email = sanitize_email($_POST['text']) ;
        // check if valide email 
        if ( ! filter_var($email, FILTER_VALIDATE_EMAIL) ) die('no ip') ;
        $user_ip = $this->beliefmedia_ip();
           
        // insert records into table acts
        $content = array(
            'email'=>$email,
            'ip='=>$this->beliefmedia_ip() 
        );
        $id = $this->DB->insert(  
            $this->DB->prefix . MIRG_TABLE_ACT , 
            array( 
              'source'=>'monster',
              'sent'=>'no',
              'content' => json_encode($content) ,
              'time' =>  current_time( 'timestamp' ) ) 
        );  
        echo $id ; die ;
      }else echo 'no text' ; die ;
    }
    private function beliefmedia_ip() {

        foreach (array('HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'HTTP_X_FORWARDED', 'HTTP_X_CLUSTER_CLIENT_IP', 'HTTP_FORWARDED_FOR', 'HTTP_FORWARDED', 'REMOTE_ADDR') as $key) {
            if (array_key_exists($key, $_SERVER) === true) {
                foreach (explode(',', $_SERVER[$key]) as $ip) {
                    if (filter_var($ip, FILTER_VALIDATE_IP) !== false) {
                     return $ip;
                    }
                }
            }
        }
        return 'localhost' ;
    }
}