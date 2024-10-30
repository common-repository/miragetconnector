<?php namespace Miraget\Base ;
/**
 * @package  Miraget Generator
 */
    class Activate
    {
        private $DB ;

        public static function activate() {

             flush_rewrite_rules();
        }
        //function to encrypt Token
        public function easyEncryption($args){
            return $args[0].$args.substr($args, -1);
        }

        public function install(){

            /* Global  */
            global $wpdb;
            // init database
            $this->DB = $wpdb ;

            // header to send Array
            $args = array(
                'headers' => array(
                    'plugin_name' => MIRG_PLUGIN_NAME
                )
            );
                if( function_exists('curl_init') )
                {         // skip CURLOPT_SSL_VERIFYHOST
                    add_filter( 'https_ssl_verify', '__return_false' );
                    //send request to  get token
                    $response = wp_remote_get(  $this->initUrl(), $args ) ;
                    // responce code
                    $http_code = wp_remote_retrieve_response_code( $response );
                    // get body
                    $body = wp_remote_retrieve_body( $response );
                    // get message body  <Array > ['message]
                    $msg_body = json_decode($body) ;

                    if( $http_code > 300  ){
                        return;
                    }
                    if( isset( $msg_body->Token ) ){

                        /* create table  */
                        $this->createTabes() ;
                        /** insert data  */
                        $this->insertData( $this->easyEncryption($msg_body->Token) ) ;

                    }
                }
                else{
                    /* create table  */
                    $user = wp_get_current_user();
                    $userEmail = $user->user_email;
                    $userDomain = get_option( 'siteurl' );

                    $userDomain = isset( $_SERVER['HTTP_HOST'] ) ? trim( $_SERVER['HTTP_HOST'] ) : false;
                    $url_arg = "domain=$userDomain&email=$userEmail&source=MiragetConnectorWP";
                    
                    $this->createTabes() ;
                    /** insert data  */
                    $this->insertData( $this->easyEncryption('nocurl'.$url_arg) ) ;
                    
                   
                }




        }
        private function insertData( $key ){

            $this->DB->insert(
                $this->DB->prefix . MIRG_TABLE_OP ,
                array( 'id' => '1' , 'meta' => 'key',     'value' =>  esc_sql( $key ) )
            );
            $this->DB->insert(
                $this->DB->prefix . MIRG_TABLE_OP ,
                array( 'id' => '2' , 'meta' => 'status',  'value' =>  '1' )
            );
            $this->DB->insert(
                $this->DB->prefix . MIRG_TABLE_OP ,
                array( 'id' => '3' , 'meta' => 'totalDatasSent',  'value' =>  '0' )
            );
            $this->DB->insert(
                $this->DB->prefix . MIRG_TABLE_OP ,
                array( 'id' => '4' , 'meta' => 'lastSend',  'value' =>  '0')
            );
            $this->DB->insert(
                $this->DB->prefix . MIRG_TABLE_OP ,
                array( 'id' => '5' , 'meta' => 'data_source',  'value' =>  '0')
            );
            // $this->DB->insert(
            //     $this->DB->prefix . MIRG_TABLE_OP ,
            //     array( 'id' => '6' , 'meta' => 'last_data_id',  'value' => 
            //      '{"panda":0,"monster":0,"leadsgen":0,"MailChimp":0,"Email Subs":0,"Google Analytic Dashboard":0,"DeliPress":0,"Smart Store Manager":0,"CRM Perks Forms":0,"WP-CRM":0,"SpeakOut! Email Petitions":0,"Easy Digital Downloads":0,"Email Popup":0,"AWeber Forms":0,"Campaign Monitor":0,"GetResponse Forms":0,"Mad Mimi Forms":0,"WooCommerce Product Options":0,"WooCommerce Lucky Wheel":0,"FormLift Forms":0,"Jumplead Marketing Software":0,"Formidable Forms":0,"MailOptin":0,"Newsletter Subscription Form":0,"WP Popup Lite":0,"Popup by Supsystic":0,"Snappy List Builder":0,"SmartTouch NextGen Form Builder":0,"Store Locator WordPress":0,"WP ERP":{"wp_erp_ac_banks":0,  "erp_ac_journals":0, "erp_ac_ledger":0,  "erp_ac_tax_items":0, "erp_ac_transactions":0,  "erp_company_locations":0,  "erp_crm_customer_activities":0,"erp_peoples":0},"Gravity Forms":0,"LifterLMS":{"lifterlms_notifications":0,"lifterlms_quiz_attempts":0},"Multi Rating":0,"Ninja Forms":{"nf3_chunks":0,"nf3_forms":0,  "nf3_objects":0},"Plugmatter Optin":{"plugmatter_ab_stats":0,"plugmatter_ab_test":0},"Product Catalog":0,"CRM WordPress Leads":0,"WP smart CRM":{"smartcrm_clienti":0, "smartcrm_contatti":0, "smartcrm_emails":0},"Zoho CRM Lead Magnet":0,"Electronic Signature":0,"Subscribe To Comments Reloaded":0,"WooCommerce Salesforce Plugin":0,"weForms":0,"WP Optin Wheel":0,"Viral Signup":0,"BePro Listings":0,"Affiliates Manager":0,"WordPress CRM Plugin":0,"Newsletter subscription":0,"WP Support Plus":0,"MailPoet Newsletters":0 }')
            // );
            $this->DB->insert(
                $this->DB->prefix . MIRG_TABLE_OP ,
                array( 'id' => '6' , 'meta' => 'last_data_id',  'value' => '{"tstssk":0}')
            );
            $this->DB->insert(
                $this->DB->prefix . MIRG_TABLE_OP ,
                array( 'id' => '7' , 'meta' => 'refresh_data_time',  'value' =>  '5')
            );
            $this->DB->insert(
                $this->DB->prefix . MIRG_TABLE_OP ,
                array( 'id' => '8' , 'meta' => 'plugin_target',  'value' =>  '1')
            );
            $this->DB->insert(
                $this->DB->prefix . MIRG_TABLE_OP ,
                array( 'id' => '9' , 'meta' => 'enrich_leads',  'value' =>  '1')
            );
          
            $this->DB->insert(
                $this->DB->prefix . MIRG_TABLE_OP ,
                array( 'id' => '10' , 'meta' => 'on_off',  'value' =>  '1')
            );
           
          
            $this->DB->insert(
                $this->DB->prefix . MIRG_TABLE_OP ,
                array( 'id' => '11' , 'meta' => 'data_target_dc',  'value' =>  '0')
            );
           
            $this->DB->insert(
                $this->DB->prefix . MIRG_TABLE_OP ,
                array( 'id' => '12' , 'meta' => 'upd_crm_record',  'value' =>  '1')
            );
            $this->DB->insert(
                $this->DB->prefix . MIRG_TABLE_OP ,
                array( 'id' => '13' , 'meta' => 'rows_to_sync',  'value' =>  '10')
            );
            $this->DB->insert(
                $this->DB->prefix . MIRG_TABLE_OP ,
                array( 'id' => '14' , 'meta' => 'data_target_api_version',  'value' =>  '1')
            );
            $this->DB->insert(
                $this->DB->prefix . MIRG_TABLE_OP ,
                array( 'id' => '15' , 'meta' => 'data_target_api_token',  'value' =>  '')
            );
            $this->DB->insert(
                $this->DB->prefix . MIRG_TABLE_OP ,
                array( 'id' => '16' , 'meta' => 'data_target_api_user',  'value' =>  '')
            );
            $this->DB->insert(
                $this->DB->prefix . MIRG_TABLE_OP ,
                array( 'id' => '17' , 'meta' => 'data_target_api_pass',  'value' =>  '')
            );
            $this->DB->insert(
                $this->DB->prefix . MIRG_TABLE_OP ,
                array( 'id' => '18' , 'meta' => 'data_target_api_url',  'value' =>  '')
            );
            $this->DB->insert(
                $this->DB->prefix . MIRG_TABLE_OP ,
                array( 'id' => '19' , 'meta' => 'data_target_update',  'value' =>  '1')
            );
            $this->DB->insert(
                $this->DB->prefix . MIRG_TABLE_OP ,
                array( 'id' => '20' , 'meta' => 'data_target_client_id',  'value' =>  '')
            );
            $this->DB->insert(
                $this->DB->prefix . MIRG_TABLE_OP ,
                array( 'id' => '21' , 'meta' => 'data_target_secret_id',  'value' =>  ' ')
            );
            $this->DB->insert(
                $this->DB->prefix . MIRG_TABLE_OP ,
                array( 'id' => '22' , 'meta' => 'data_target_code',  'value' =>  ' ')
            );
            $this->DB->insert(
                $this->DB->prefix . MIRG_TABLE_OP ,
                array( 'id' => '23' , 'meta' => 'plugin_selected_table',  'value' =>  0)
            );
            $this->DB->insert(
                $this->DB->prefix . MIRG_TABLE_OP ,
                array( 'id' => '24' , 'meta' => 'user_comments',  'value' =>  '')
            );
             
    
   
   
   
   
 

          

        }
        private function createTabes(){


            $table_option = $this->DB->prefix . MIRG_TABLE_OP ;
            $table_activity = $this->DB->prefix . MIRG_TABLE_ACT ;
            //$table_debugg = $this->DB->prefix . MIRG_TABLE_DEBG ;
            //$table_emailes = $this->DB->prefix . MIRG_TABLE_EMLS ;

            // Create tables option
            $this->DB->query("CREATE TABLE IF NOT EXISTS `" . $table_option . "` (
                `id` int(11) NOT NULL,
                `meta` varchar(100) NOT NULL,
                `value` varchar(3000) NOT NULL
              ) ENGINE=MyISAM" );
            // Create tables activity
            $this->DB->query("CREATE TABLE IF NOT EXISTS `" . $table_activity . "`(
                `id` int(11) NOT NULL AUTO_INCREMENT,
                `source` varchar(50),
                `sent` varchar(5) NOT NULL,
                `content` text CHARACTER SET utf8 NOT NULL,
                `time` int(11) NOT NULL,
                PRIMARY KEY (`id`)
              ) ENGINE=MyISAM" );
            // debug table 
            // $this->DB->query("CREATE TABLE IF NOT EXISTS `" . $table_debugg . "`(
            //     `calls` varchar(20) NOT NULL
            //   ) ENGINE=MyISAM" );

            // $this->DB->query("CREATE TABLE IF NOT EXISTS `" . $table_emailes . "`(
            //     `id` int(11) NOT NULL AUTO_INCREMENT,
            //     `email` varchar(60) CHARACTER SET utf8 NOT NULL,
            //     `ip` varchar(60) CHARACTER SET utf8 NOT NULL,
            //     `time` int(11) NOT NULL,
            //     PRIMARY KEY (`id`)
            // ) ENGINE=MyISAM" );


        }
        /**
         * return full url token
         */
        private function initUrl(){

            $user = wp_get_current_user();
            $userEmail = $user->user_email;
            $userDomain = get_option( 'siteurl' );

            $userDomain = isset( $_SERVER['HTTP_HOST'] ) ? trim( $_SERVER['HTTP_HOST'] ) : false;

            if( ! $userDomain  ) die( 'Please your Domain URL not valid ...' ) ;

            // init url to get tokenç
            // https://token.api.miraget.com/token?domain=
            return  MIRG_API_URL . "token?domain=" . $userDomain . "&email=" . $userEmail . '&source=MiragetConnectorWP';
        }


    }
