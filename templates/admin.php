<?php
use Mireget\page\CronSApi;
class MiragetAdminTemplate
{
    private $DB ;
    private $miraget_nonce ;
    private $error_post =  false  ;
    private $is_post =  false  ;
    private $records =  []  ;
    private $lastUpda ;
    private $updta_ok = false ;
    private $refresh_data  ;
    private $plugin_source  ;
    private $plugin_target  ;
    private $plugin_target_version  ;

   
    
    
 

    function __construct(){

        global $wpdb;
        $this->DB = $wpdb;
        $this->getApitStatus() ;
        $this->miraget_nonce = wp_create_nonce( 'miraget_nonce' );
        // insert data if isset post
        $this->postInit() ;
        
        // get records
        $this->getRecords() ;

        //add_action('admin_post_purge-history', array( $this, 'purge_history') );

        //$this->purgeurl = admin_url() . 'admin.php?page=purgehistory';
        $this->purgeurl = get_admin_url() . 'admin-post.php';
        //print_r($this->purgeurl);
        
    }


    function bl_cron_hook() {
    }

    // function purge_history(){
       

    //     //if(isset( $_POST['purge-history'] ) ){
    //         global $wpdb;
    //         $table = $wpdb->prefix. MIRG_TABLE_ACT ;
    //         $delete = $wpdb->query("TRUNCATE TABLE $table");

    //         wp_redirect(admin_url() .'admin.php?page=MiragetConnector_crm_plugin');
    //     //}
    // }

    private function getRecords(){

      
        
        
     
        //$source = 'MiragetConnector';
        $table = $this->DB->prefix . MIRG_TABLE_ACT ;
        $fivesdrafts = $this->DB->get_results(
            " SELECT id, content, time
              FROM $table
              WHERE `sent` = 'yes' 
              ORDER BY `time` DESC
              LIMIT 8
            "
        );
       
      
        foreach ( $fivesdrafts as $fivesdraft )
        {
            $this->records[] =  $fivesdraft ;
        }

    }

    //function to get all active plugins
    private function getActivPlugins(){

        $active_plugins = get_option('active_plugins');
        $active_plugins_names= array();
        foreach ($active_plugins as $plugin) {
            $patter = '/\/.*/i';
            $plugin = preg_replace($patter,'',$plugin);
            array_push($active_plugins_names,$plugin);
            
        }
        return $active_plugins_names;
    }
    //function to get all wordpress table, return array
    private function getAllSqlTables(){
        // wordpress tables
        $wp_tables = $this->DB->tables();
        // all tables 
        $all_tables= array();
        //regex to filter wp table
        $pattern = '/'.$this->DB->prefix.'/i';
        $mytables=$this->DB->get_results("SHOW TABLES");
        foreach ($mytables as $mytable)
        {
            foreach ($mytable as $t) 
            {   
               //filter wordpress tables    
            //    if( !in_array($t,$wp_tables) ) 
            //     {
                    $t= preg_replace($pattern,'',$t);
                    array_push($all_tables,$t);
                //}
            }
        }
        return $all_tables;
    }
    
    private function postInit() {
         
        
        if( count($_POST) >= 13 && count($_POST)<= 21){
           
             
            
          
            $mc_plugin_target = (int) sanitize_text_field($_POST['miraget_plugin_target']);
            if( $mc_plugin_target===1 ) $we_are_in='zoho';     
            if( $mc_plugin_target===2 ) $we_are_in='salesmate';
            if( $mc_plugin_target===3 ) $we_are_in='insightly';
            if( $mc_plugin_target >3 && $mc_plugin_target<8 ) {
                $we_are_in='amazon';
                
            }
            if( $mc_plugin_target===8 ) $we_are_in='salesforces1';
            if( $mc_plugin_target===9 ) $we_are_in='salesforces2';
            //if( $mc_plugin_target===10 ) $we_are_in='salesforces3';

            if($mc_plugin_target === 10 || $mc_plugin_target === 11){
                $we_are_in = 'fullcontact';
            }
            if( $mc_plugin_target===12 ) $we_are_in='marketo';
            if( $mc_plugin_target===13 ) $we_are_in='splunk';

            //new targets
            if( $mc_plugin_target===14 ) $we_are_in='netsuite';
            if( $mc_plugin_target===15 ) $we_are_in='snowflake';
            if( $mc_plugin_target===16 ) $we_are_in='centriccrm';
            if( $mc_plugin_target===17 ) $we_are_in='zendesk';
            if( $mc_plugin_target===18 ) $we_are_in='servicenow';
            if( $mc_plugin_target===19 ) $we_are_in='googlebigquery';
            if( $mc_plugin_target===20 ) $we_are_in='apachehive';
            if( $mc_plugin_target===21 ) $we_are_in='jira';
            if( $mc_plugin_target===22 ) $we_are_in='sage';

            // zoho filed ;
            if( $we_are_in === 'zoho')
              {
                  $additional_post = array(
                    'miraget_plugin_zoho_vars', 'zoho_token','client_id', 'code', 'secret_id','zoho_datacenter', 'upd_crm_record'
                    ) ;
              }

            // salesmate filed ;
            if( $we_are_in === 'salesmate')
            $additional_post = array('salesmate_user', 'salesmate_pass','salesmate_session', 'salesmate_url') ;
            //insightly fields
            if( $we_are_in === 'insightly')
            $additional_post = array('insightly_access_key', 'insightly_secret_key','insightly_session_key', 'insightly_target_api', 'insightly_token_api');
            // azure or microsoft fields
            if( $we_are_in === 'amazon')
            $additional_post = array('host', 'port','user', 'pwd', 'database');
             // azure or microsoft fields
             if( $we_are_in === 'salesforces1')
             $additional_post = array('user', 'pwd', 'security_token');
              // azure or microsoft fields
            if( $we_are_in === 'salesforces2')
            $additional_post = array('issuer', 'subject', 'expiration_time', 'key_store','keystore_pwd','security_token');
            if( $we_are_in === 'salesforces3')
            $additional_post = array('client_id', 'cb_host','client_secret', 'cb_port', 'token_file');
            
            if($we_are_in ==='fullcontact'){
                $additional_post = array('client_id','client_secret','fl-code');
            }
            if($we_are_in ==='marketo'){
                $additional_post = array('endpoint','client-class-id','secret-key');
            }
            if($we_are_in ==='splunk'){
                $additional_post = array('splunk-url','splunk-token');
            }
            if($we_are_in ==='netsuite'){
                $additional_post = array('netsuite-endpoint','netsuite-email','netsuite-password','netsuite-role',
                                          'netsuite-account','netsuite-appid','netsuite-recordtype'  );
            }
            if($we_are_in ==='snowflake'){
                $additional_post = array('snowflake-account','snowflake-userid','snowflake-password','snowflake-warehouse',
                                         'snowflake-schema','snowflake-database' ,'snowflake-table'  );
            }
            if($we_are_in ==='centriccrm'){
                $additional_post = array('centric-url','centric-module','centric-server','centric-userid','centric-password');
            }
            if($we_are_in ==='zendesk'){
                $additional_post = array('zendesk-url','zendesk-username','zendesk-password');
            }
            if($we_are_in ==='servicenow'){
                $additional_post = array('servicenow-url','servicenow-username','servicenow-password','servicenow-table');
            }
            if($we_are_in ==='googlebigquery'){
                $additional_post = array('googlebg-clientid','googlebg-clientsecret','googlebg-projectid','googlebg-authcode',
                                          'googlebg-dataset','googlebg-table');
            }
            if($we_are_in ==='apachehive'){
                $additional_post = array('apachehive-host','apachehive-port','apachehive-database','apachehive-username',
                                           'apachehive-password' );
            }
            if($we_are_in ==='jira'){
                $additional_post = array('jira-url','jira-userid','jira-password','jira-ressource','jira-jql');
            }
            if($we_are_in ==='sage'){
                $additional_post = array('sage-endpoint','sage-user','sage-password','sage-language','sage-pollalias',
                                          'sage-requestconfig','sage-publicationname','sage-action'  );
            }


            // standart fileds
            if( empty($table_checkbox))
            {
                $array_post = array( 'miraget_key' ,'m_none' ,'save',  'miraget_plugin_refresh_data','on_off', 'miraget_plugin_target',
                                   'rows_to_sync','plugin-source','miraget_enrich_leads','comment','table-name-input' ) ;
                              
            }
            else  $array_post = array( 'miraget_key' ,'m_none' ,'save',  'miraget_plugin_refresh_data','on_off', 'miraget_plugin_target',
             'rows_to_sync','plugin-source','miraget_enrich_leads','comment','multi-table-sync' ) ;
            // all post in the request
            $result = array_merge($additional_post, $array_post); 
            
            
            if(isset($_POST['hidden-field']) &&  empty($_POST['multi-table-sync'] )){
                $this->error_post = true ;
                $this->is_post = true ;
            }
            if( !isset($_POST['hidden-field']) &&  empty($_POST['table-name-input'] )){
                $this->error_post = true ;
                $this->is_post = true ;
            }

            
           
            

            foreach ($_POST as $key => $value) {
                
                # chek condition...
                if($key != 'multi-table-sync'  && $key !='hidden-field' && $key != 'table-name-input1'
                 && $key != 'table-name-input' && $key != 'snowflake-table' && $key != 'servicenow-table' 
                 && $key != 'googlebg-table'){
                    if(
                        ! in_array( sanitize_key( $key), $result ) ||
                        sanitize_text_field( trim( $value ) === '' && ($key != 'comment') ) ||
                    ! wp_verify_nonce( sanitize_text_field( $_POST['m_none']) , 'miraget_nonce' )
                    
                    ) {
                        print_r('field missing = '.$key);
                        $this->error_post = true ;
                        $this->is_post = true ;
                        
                    }
                }
               
                

            }

            // update data
            if(  ! $this->error_post ){
               //print(" ok updating");
                $this->updateData($we_are_in) ;
            }
        }
    }
 //encryption , decryption functions
    private function easyEncryption($args){
        if($args != ''){return $args[0].$args.substr($args, -1);}
        else return $args;
      }
     private  function easyDecryption($args){
        if($args != ''){$value = substr($args,1);
        return substr($value,0, -1);} 
        else return $args;  
       }

    private function updateData($type){

        $dataKey     = $this->easyEncryption(sanitize_text_field( $_POST['miraget_key'] ) );
        $on_off_val  =  (int) $_POST['on_off'] ;
        $on_off   =  ( $on_off_val === 0 OR $on_off_val === 1) ? $on_off_val : 0  ;
        //enrich lead
        $enrich_lead = (int) sanitize_text_field($_POST['miraget_enrich_leads']);
        /************************ */
        $refresh_data = (int) $_POST['miraget_plugin_refresh_data'] ;
        $data2Refresh = ($refresh_data >= 1 && $refresh_data <= 15 && ($refresh_data % 5 === 0 ) ) ? $refresh_data : 1 ;
        
        $plugin_source =  sanitize_text_field($_POST['plugin-source']) ;

        //case plugin with multiple tables add table to sync 
        
       
        if(isset($_POST['multi-table-sync'])  ) 
        { 
           $plugin_selected_tables = $_POST['multi-table-sync'];
           //print_r('case multiple tables plugin multiple');
        }
        else {
            $plugin_selected_tables =array(sanitize_text_field($_POST['table-name-input']));
            $get_tables = sanitize_text_field($_POST['table-name-input']);
            $get_tables = preg_replace('/,$/','',$get_tables);
            $plugin_selected_tables = explode(',',$get_tables);
            //print_r('case one tables plugin one');
        }
        //$post_var_plugin_source = 1 ;
        $post_var_plugin_target = (int) $_POST['miraget_plugin_target'] ;
       
        //$plugin_target =  ( $post_var_plugin_target === 1 
        //|| $post_var_plugin_target === 2 || $post_var_plugin_target === 3 )  ? $post_var_plugin_target : 1 ;
        $plugin_target = $post_var_plugin_target;
        if( $type === 'zoho'){

            $api_v = (int) $_POST['miraget_plugin_zoho_vars'] ;
            
          
            if($api_v == 1){
                
                $array2Update = array(

                    '14' =>  $api_v  ,
                    '15' => $this->easyEncryption(sanitize_text_field( $_POST['zoho_token'] )) ,
                    '19' => sanitize_text_field( $_POST['upd_crm_record'] )
    
                ) ;
            }
            if($api_v == 2){

               
                $array2Update = array(

                    '14' => $api_v ,
                    '20' => $this->easyEncryption(sanitize_text_field( $_POST['client_id'] )) ,
                    '22' => $this->easyEncryption(sanitize_text_field( $_POST['code'] )) ,
                    '11' => sanitize_text_field( $_POST['zoho_datacenter']),
                    '21' => $this->easyEncryption(sanitize_text_field( $_POST['secret_id'] )),
                    '19' => sanitize_text_field( $_POST['upd_crm_record'] )
                );
            }
        }
        if( $type === 'salesmate'){
            
            $array2Update = array(

                '16' => $this->easyEncryption(sanitize_text_field( $_POST['salesmate_user'] )),//Access Key
                '17' => $this->easyEncryption(sanitize_text_field( $_POST['salesmate_pass'] )),//Secret Key
                '18' => sanitize_text_field( $_POST['salesmate_url'] ) ,//url
                '15' => $this->easyEncryption(sanitize_text_field( $_POST['salesmate_session'] ))//sesseion key

            ) ;
           
        }
        //if insightly
        if( $type ==='insightly'){
            $array2Update = array(

                '16' => $this->easyEncryption(sanitize_text_field( $_POST['insightly_access_key'] )),
                '17' => $this->easyEncryption(sanitize_text_field( $_POST['insightly_secret_key'] )),
                '22' => $this->easyEncryption(sanitize_text_field( $_POST['insightly_session_key'] )) ,
                '18' => sanitize_text_field( $_POST['insightly_target_api'] ), 
                '15' => $this->easyEncryption(sanitize_text_field( $_POST['insightly_token_api'] ))
            ) ;

        }
        if( $type ==='amazon'){
            
            $array2Update = array(

                '18' => sanitize_text_field( $_POST['host'] ),
                '22' => $this->easyEncryption(sanitize_text_field( $_POST['port'] )),
                '16' => $this->easyEncryption(sanitize_text_field( $_POST['user'] )) ,
                '17' => $this->easyEncryption(sanitize_text_field( $_POST['pwd'] )), 
                '15' => $this->easyEncryption(sanitize_text_field( $_POST['database'] ))
            ) ;

        }
        if( $type ==='salesforces1'){
            
            $array2Update = array(
                
                '16' => $this->easyEncryption(sanitize_text_field( $_POST['user'] )),
                '17' => $this->easyEncryption(sanitize_text_field( $_POST['pwd'] )),
                '15' => $this->easyEncryption(sanitize_text_field( $_POST['security_token'] )) ,
            ) ;

        }
        if( $type ==='salesforces2'){
            
            $array2Update = array(

                '16' => $this->easyEncryption(sanitize_text_field( $_POST['key_store'] )),
                '17' => $this->easyEncryption(sanitize_text_field( $_POST['subject'] )),
                '22' => $this->easyEncryption(sanitize_text_field( $_POST['expiration_time'] )) ,
                '18' => sanitize_text_field( $_POST['issuer'] ), 
                '15' => $this->easyEncryption(sanitize_text_field( $_POST['keystore_pwd'] )),
                '20' => $this->easyEncryption(sanitize_text_field( $_POST['security_token'] ))
            ) ;

        }
        if( $type ==='fullcontact'){
            
            $array2Update = array(
                 
                '16' => $this->easyEncryption(sanitize_text_field( $_POST['client_id'] )),
                '17' => $this->easyEncryption(sanitize_text_field( $_POST['client_secret'] )),
                '15' => $this->easyEncryption(sanitize_text_field( $_POST['fl-code'] )) ,
            ) ;

        }
        if( $type ==='marketo'){
            
            $array2Update = array(
                 
                '16' => $this->easyEncryption(sanitize_text_field( $_POST['endpoint'] )),
                '17' => $this->easyEncryption(sanitize_text_field( $_POST['client-class-id'] )),
                '15' => $this->easyEncryption(sanitize_text_field( $_POST['secret-key'] )) ,
            ) ;

        }
        if( $type ==='splunk'){
            
            $array2Update = array(
                 
                '18' => sanitize_text_field( $_POST['splunk-url'] ),
                '15' => $this->easyEncryption(sanitize_text_field( $_POST['splunk-token'] )),
               
            ) ;

        }
        if( $type ==='netsuite'){
            
            $array2Update = array(
                 
                '16' => $this->easyEncryption(sanitize_text_field( $_POST['netsuite-endpoint'] )),
                '17' => $this->easyEncryption(sanitize_text_field( $_POST['netsuite-email'] )),
                '22' => $this->easyEncryption(sanitize_text_field( $_POST['netsuite-password'] )) ,
                '18' => sanitize_text_field( $_POST['netsuite-role'] ), 
                '15' => $this->easyEncryption(sanitize_text_field( $_POST['netsuite-account'] )),
                '20' => $this->easyEncryption(sanitize_text_field( $_POST['netsuite-appid'] )),
                '21' => $this->easyEncryption(sanitize_text_field( $_POST['netsuite-recordtype'] ))
               
            ) ;

        }
        if( $type ==='snowflake'){
            
            $array2Update = array(
                 
                '16' => $this->easyEncryption(sanitize_text_field( $_POST['snowflake-account'] )),
                '17' => $this->easyEncryption(sanitize_text_field( $_POST['snowflake-userid'] )),
                '22' => $this->easyEncryption(sanitize_text_field( $_POST['snowflake-password'] )) ,
                '18' => sanitize_text_field( $_POST['snowflake-warehouse'] ), 
                '15' => $this->easyEncryption(sanitize_text_field( $_POST['snowflake-schema'] )),
                '20' => $this->easyEncryption(sanitize_text_field( $_POST['snowflake-database'] )),
                '21' => $this->easyEncryption(sanitize_text_field( $_POST['snowflake-table'] ))
               
            ) ;

        }
        if( $type ==='centriccrm'){
            
            $array2Update = array(
                 
                '16' => $this->easyEncryption(sanitize_text_field( $_POST['centric-url'] )),
                '17' => $this->easyEncryption(sanitize_text_field( $_POST['centric-module'] )),
                '22' => $this->easyEncryption(sanitize_text_field( $_POST['centric-server'] )) ,
                '18' => sanitize_text_field( $_POST['centric-userid'] ), 
                '15' => $this->easyEncryption(sanitize_text_field( $_POST['centric-password'] )),
        
               
            ) ;

        }
        if( $type ==='zendesk'){
            
            $array2Update = array(
                 
                '16' => $this->easyEncryption(sanitize_text_field( $_POST['zendesk-url'] )),
                '17' => $this->easyEncryption(sanitize_text_field( $_POST['zendesk-username'] )),
                '22' => $this->easyEncryption(sanitize_text_field( $_POST['zendesk-password'] )) ,
               
            ) ;

        }
        if( $type ==='servicenow'){
            
            $array2Update = array(
                 
                '16' => $this->easyEncryption(sanitize_text_field( $_POST['servicenow-url'] )),
                '17' => $this->easyEncryption(sanitize_text_field( $_POST['servicenow-username'] )),
                '22' => $this->easyEncryption(sanitize_text_field( $_POST['servicenow-password'] )) ,
                '18' => sanitize_text_field( $_POST['servicenow-table'] ), 
                
        
               
            ) ;

        }
        if( $type ==='googlebigquery'){
            
            $array2Update = array(
                 
                '16' => $this->easyEncryption(sanitize_text_field( $_POST['googlebg-clientid'] )),
                '17' => $this->easyEncryption(sanitize_text_field( $_POST['googlebg-clientsecret'] )),
                '22' => $this->easyEncryption(sanitize_text_field( $_POST['googlebg-projectid'] )) ,
                '18' => sanitize_text_field( $_POST['googlebg-dataset'] ), 
                '15' => $this->easyEncryption(sanitize_text_field( $_POST['googlebg-authcode'] )),
                '20' => $this->easyEncryption(sanitize_text_field( $_POST['googlebg-table'] )),
                
               
            ) ;

        }
        if( $type ==='apachehive'){
            
            $array2Update = array(
                 
                '16' => $this->easyEncryption(sanitize_text_field( $_POST['apachehive-password'] )),
                '17' => $this->easyEncryption(sanitize_text_field( $_POST['apachehive-port'] )),
                '22' => $this->easyEncryption(sanitize_text_field( $_POST['apachehive-database'] )) ,
                '18' => sanitize_text_field( $_POST['apachehive-host'] ), 
                '15' => $this->easyEncryption(sanitize_text_field( $_POST['apachehive-username'] )),

            ) ;

        }
        if( $type ==='jira'){
            
            $array2Update = array(
                 
                '16' => $this->easyEncryption(sanitize_text_field( $_POST['jira-url'] )),
                '17' => $this->easyEncryption(sanitize_text_field( $_POST['jira-userid'] )),
                '22' => $this->easyEncryption(sanitize_text_field( $_POST['jira-password'] )) ,
                '18' => sanitize_text_field( $_POST['jira-ressource'] ), 
                '15' => $this->easyEncryption(sanitize_text_field( $_POST['jira-jql'] )),

            ) ;

        }
        if( $type ==='sage'){
            
            $array2Update = array(
                 
                '16' => $this->easyEncryption(sanitize_text_field( $_POST['sage-endpoint'] )),
                '17' => $this->easyEncryption(sanitize_text_field( $_POST['sage-user'] )),
                '22' => $this->easyEncryption(sanitize_text_field( $_POST['sage-password'] )) ,
                '18' => sanitize_text_field( $_POST['sage-language'] ), 
                '15' => $this->easyEncryption(sanitize_text_field( $_POST['sage-pollalias'] )),
                '20' => $this->easyEncryption(sanitize_text_field( $_POST['sage-requestconfig'] )),
                '21' => $this->easyEncryption(sanitize_text_field( $_POST['sage-publicationname'] )),
                '19' => sanitize_text_field( $_POST['sage-action'] )
               
            ) ;

        }
       
         //************************************************** 
          $table = $this->DB->prefix . MIRG_TABLE_OP  ;
          $row_2_sync = (int) $_POST['rows_to_sync'] ;
          $user_comment = sanitize_text_field( $_POST['comment'] );
        
        if( $row_2_sync === 1 || $row_2_sync === 10 || $row_2_sync === 20 || $row_2_sync === 30  ){

          $update_id_value = array(
                '1' => $dataKey ,
                '5' => $plugin_source ,
                '7' => $data2Refresh ,
                '8' => $plugin_target ,
                '10' => $on_off  ,
                '13' => $row_2_sync  ,
                '9' => $enrich_lead,
                //'23'=> $plugin_selected_table,
                '23'=>json_encode($plugin_selected_tables),
                '24'=>$user_comment
                
            );
            

            $rsult_arr_2_update =  ( $update_id_value + $array2Update ) ;

            foreach ( $rsult_arr_2_update as $id => $value ) {

                $this->DB->update( $table,
                    array( 'value' => $value  ),
                    array( 'id' => $id ),
                    array( '%s',  ),
                    array( '%s' )
                );
            }
            $this->updta_ok = true ;


        }

    }
    public function render(){

        return   '
          currentPage = "main" ;
          var apiStatus = "' .  $this->api_status . '" ;
          var miragetKey = "' .  $this->key . '" ;
          var emailes =  ' . json_encode($this->records) . '  ;
          var activePlugins =  ' . json_encode($this->getActivPlugins()) . '  ;
          var allTables =  ' . json_encode($this->getAllSqlTables()) . '  ;
          var errors_post =  ' .( int )  $this->error_post . '  ;
          var update =  ' .( int )  $this->updta_ok . '  ;
          var pluginSource = "' . $this->data_source . '"  ;
          var enrichLeads = ' .( int )  $this->enrich_leads . '  ;
          var pluginTarget = ' .( int )  $this->plugin_target . '  ;
          var pluginTargetVersion = ' .( int )  $this->data_target_api_version. '  ;
          var zohoToken = "' . $this->data_target_api_token . '"  ;
          var salesmateUser = "' . $this->data_target_api_user . '"  ;
          var salesmatePass = "' . $this->data_target_api_pass . '"  ;
          var salesmateUrl = "' . $this->data_target_api_url . '"  ;
          var refresh_data = ' .( int )  $this->refresh_data . '  ;
          var is_post =  ' .( int )  $this->is_post . '  ;
          var lastUpdate =  ' .( int )  $this->lastUpda . '  ;
          var onOff =  ' .( int )  $this->on_off . '  ;
          var isWorkable =  '.  $this->isWorkable() .' ;
          var nonce = "' .  $this->miraget_nonce . '" ;
          var clientId = "' .  $this->data_target_client_id . '" ;
          var codeZoho = "' .  $this->data_target_code . '" ;
          var secretId = "' . $this->data_target_secret_id . '" ;
          var zohoDataCenter =  ' .  ( int ) $this->data_target_dc . '  ;
          var updCrmRecord =  ' .  ( int ) $this->data_target_update . '  ;
          var rowsToSync =  ' .  ( int ) $this->rows_to_sync . '  ;
          var pluginSelectedTable =  ' .  json_encode($this->plugin_selected_table ). '  ;
          var insightlyAccessKey = "' . $this->data_target_api_user.'";
          var field19 = "' . $this->data_target_update.'";
          var insightlySecretKey = "' . $this->data_target_api_pass.'";
          var insightlySessionKey = "' . $this->data_target_code.'";
          var insightlyTargetApi = "' . $this->data_target_api_url .'";
          var insightlyTokenApi = "' . $this->data_target_api_token.'";
          var purgeUrl = " '.$this->purgeurl.' ";
          var userComment = "' . $this->user_comment.'";
          var pluginUrl = "' . MIRG_PLUGIN_URL .'";
          
          var pageTitle = "MiragetGeneartor info" ;
        ' ;
    }


    public function getApitStatus(){

        $this->table_option = $this->DB->prefix . MIRG_TABLE_OP ;

        $this->key = $this->easyDecryption($this->DB->get_var( "select value from $this->table_option where id='1'" ));
        $this->api_status = $this->DB->get_var( "select value from $this->table_option where id='2'" );
        $this->lastUpda = $this->DB->get_var( "select value from $this->table_option where id='4'" );
        $this->data_source = $this->DB->get_var( "select value from $this->table_option where id='5'" );
        $this->refresh_data = $this->DB->get_var( "select value from $this->table_option where id='7'" );
        $this->plugin_target = $this->DB->get_var( "select value from $this->table_option where id='8'" );
        $this->enrich_leads = $this->DB->get_var( "select value from $this->table_option where id='9'" );
        $this->on_off = $this->DB->get_var( "select value from $this->table_option where id='10'" );
        $this->data_target_dc = $this->DB->get_var( "select value from $this->table_option where id='11'" );
        $this->update_crm_record = $this->DB->get_var( "select value from $this->table_option where id='12'" );
        $this->rows_to_sync = $this->DB->get_var( "select value from $this->table_option where id='13'" );

        $this->data_target_api_version = $this->DB->get_var( "select value from $this->table_option where id='14'" );
        $this->data_target_api_token = $this->easyDecryption($this->DB->get_var( "select value from $this->table_option where id='15'" ));
        $this->data_target_api_user = $this->easyDecryption($this->DB->get_var( "select value from $this->table_option where id='16'" ));
        $this->data_target_api_pass = $this->easyDecryption($this->DB->get_var( "select value from $this->table_option where id='17'" ));
        $this->data_target_api_url = $this->DB->get_var( "select value from $this->table_option where id='18'" );
        $this->data_target_update = $this->DB->get_var( "select value from $this->table_option where id='19'" );
        $this->data_target_client_id = $this->easyDecryption($this->DB->get_var( "select value from $this->table_option where id='20'" ));
        $this->data_target_secret_id = $this->easyDecryption($this->DB->get_var( "select value from $this->table_option where id='21'" ));
        $this->data_target_code = $this->easyDecryption($this->DB->get_var( "select value from $this->table_option where id='22'" ));
        $this->plugin_selected_table = $this->DB->get_var( "select value from $this->table_option where id='23'" );
        $this->user_comment = $this->DB->get_var( "select value from $this->table_option where id='24'" );
    }


    private function isWorkable(){
        //zoho v1
      if(
            $this->data_target_api_token &&
            (int) $this->plugin_target === 1 &&
            (int) $this->data_target_api_version > 0 
            //(int) $this->data_source >0
        )  return 'true' ;
        //zoho v2
        if(
            $this->data_target_code &&
            (int) $this->plugin_target === 1 &&
            (int) $this->data_target_api_version === 2 &&
            $this->data_target_secret_id &&
            $this->data_target_client_id 
            //(int) $this->data_source >0

        )  return 'true' ;
        //salesmate
        if(
                $this->data_target_api_user &&
                ( (int) $this->plugin_target === 2 || 
                 (int) $this->plugin_target === 8 ) &&
                $this->data_target_api_url  &&
                $this->data_target_api_pass 
                //(int) $this->data_source >0
            ) return 'true' ;
        //check if user input insightly fields
        if(
                $this->data_target_api_user &&
                ((int) $this->plugin_target >= 3 &&
                (int) $this->plugin_target <8 ) &&
                $this->data_target_api_url  &&
                $this->data_target_api_pass &&
                $this->data_target_api_token &&
                $this->data_target_code 
                //(int) $this->data_source >0
            
        ) return 'true' ;
        if(
            $this->data_target_api_user &&
            (int) $this->plugin_target === 9  &&
            $this->data_target_api_url  &&
            $this->data_target_api_pass &&
            $this->data_target_api_token &&
            $this->data_target_code &&
            $this->data_target_client_id 
            //(int) $this->data_source >0
        
      ) return 'true' ;
      //salesforces 3
      if(
        $this->data_target_api_user &&
        (int) $this->plugin_target === 10 &&
       
        $this->data_target_api_url  &&
        $this->data_target_api_pass &&
        $this->data_target_api_token &&
        $this->data_target_code 
        //(int) $this->data_source >0
    
        ) return 'true' ;
        //new targets
        if(
            
            ((int) $this->plugin_target >= 11 || (int) $this->plugin_target <=12)&&
            $this->data_target_api_user &&
            $this->data_target_api_pass &&
            $this->data_target_api_token

            ) return 'true' ;
    

        if(
            (int) $this->plugin_target === 13 &&
            $this->data_target_api_url  &&
            $this->data_target_api_token 
        
            ) return 'true';
        
        if(
            (int) $this->plugin_target >= 13 
            ) return 'true';

        

        return 'false' ;

    }
}

