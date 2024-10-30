<?php 
namespace Miraget\Pages ;
/**
 * @package  Miraget Generator
 * insert emails if exist
 */
/*
use \Miraget\Api\SettingsApi ;
use \Miraget\Api\Callbacks\AdminCallbacks ; */
class CronSApi
{
    
    private $table_option ;
    private $time2Update   ; // minutes
    private $recordsTable  ;    // records table to send to integrator
    private $lastUpdateTime ; // unix time
    private $curlApi = 'https://cloud.miraget.com/simple' ;
    private $token   ;
    private $lasEmailedId   ;
    private $pluginSource;


 
  
    function __construct(){

        global $wpdb;
        $this->DB = $wpdb;
        // table option
        $this->table_option = $this->DB->prefix . MIRG_TABLE_OP  ;
        // panda table
        //
        $this->comment = $this->DB->get_var( "select value from $this->table_option where id='24'" );
        $this->chooseRecordsTable();
        // init last emailed id
        //$this->lastRecordIdFound() ;
        $this->token = $this->myToken() ;
        
    }
    //function to decrypt token
    function easyDecryption($args){
        $value = substr($args,1);
        return substr($value,0, -1);   
       }
     
    
    public function chooseRecordsTable(){
        

    
    $this->pluginSource =  $this->DB->get_var( "select value from $this->table_option where id='5'" );
    $this->plugin_selected_table =  $this->DB->get_var( "select value from $this->table_option where id='23'" );
    $this->recordsTables = json_decode($this->plugin_selected_table,true);
    
    
    }

    public function register(){
        
        // check if new records to send 
        
            #print_r('should be only be added to front end');
            add_action( 'wp_footer',  array( $this, 'newRecords' ) );
           
            
        
       

    }

   
    private function pluginUpdate(){
        
        if (  $this->getLastSend() && !empty($this->recordsTables) ) {
           
            //print($this->getLastSend());
            
            
            foreach ($this->recordsTables as $tab) {
                    $table = $this->DB->prefix . $tab ;
                    
                    $this->lastRecordId = $this->lastRecordIdFound($table) ;
                    $recordsTable_first_column = $this->DB->get_var(
                    "SELECT column_name FROM information_schema.columns
                     WHERE table_name = '$table' ",0 
                    );
                    $leid = ( int ) $this->lastRecordId;//last record sent ID
                    $table_option = $this->DB->prefix . MIRG_TABLE_OP  ;
        
                    $limit = $this->DB->get_var( "select value from $table_option where id='13'" );
                    $where = $leid > 0 ? " WHERE `$recordsTable_first_column` >  $leid " : null ;

                    $recordsTosend = $this->DB->get_results(
                        "SELECT * FROM $table $where LIMIT " . (int) $limit, ARRAY_A 
                    );
                    //escape simple and double quotes 
                    $cc = $this->comment;
                    $cc = preg_replace('/\'|\"/i','`',$cc);
                    // array to send to miraget
                    $data_to_send   = array($tab=>$recordsTosend,'comments'=>$cc) ;    
                
                    
                    if(!empty ($recordsTosend) )
                    {
                        $is_sent = $this->send2Integrator( $data_to_send );
                        //update data in table option if miraget response: success
                        if($is_sent)
                        { 
                           $this->updateLastRecordID( end( $recordsTosend)[$recordsTable_first_column],$table ) ;
                           $this->updateLastTime();
                        }
                    }

            }
         

        }
     
    }



    private function updateLastRecordID( $obj,$table ){

        $istable = false;
        $table_option = $this->DB->prefix . MIRG_TABLE_OP  ;
        $lastRec = $this->DB->get_var( "select value from $table_option where id='6'" );
        $arrayLastRec = json_decode($lastRec,true);
        //table already exist 
        foreach ($arrayLastRec as $key => $value) {
            if($key == $table){
                $arrayLastRec[$key] = $obj;
                $istable = true;
            }
        }
        if(!$istable){
            $new_table_id = array($table=>$obj);
            
            $new_array_to_save = array_merge($arrayLastRec,$new_table_id);
            
            $is_update = $this->DB->update( $table_option,
            array(
                    'value' => json_encode($new_array_to_save) ,	
                ),
                array( 'id' => 6 ),
                array(
                    '%s',
                ),
                array( '%d' )
            );
        }
        
        else{
            $is_update = $this->DB->update( $table_option,
                array(
                        'value' => json_encode($arrayLastRec) ,	// string
                    ),
                    array( 'id' => 6 ),
                    array(
                        '%s',
                    ),
                    array( '%d' )
                );
        }
    }

    //function to formate each row as json
    private function dataToJson($tabl_rows){
        
        return json_encode($tabl_rows);
    }

    private function send2Integrator($e){
        
         $enrich = ($this->enrichLeads=='1')? 'true': 'false';
         $contentt = $this->dataToJson($e); 
        

            $curlBody = array(

                "domain" => $_SERVER['HTTP_HOST'] ,
                "data_source"  => $this->pluginSource ,
                "content" => $contentt,
                "Enrich"    => $enrich
            ) ;

       return $this->curl2Apit( $curlBody ) ;
    }


    private function curl2Apit( $body ){

        // merg array
        $data_body = $this->getDatasToSend() + $body ;
         
        $response = wp_remote_post( $this->curlApi, array(
            'method' => 'POST',
            'headers' => array( 'Content-Type' => 'application/json' , 'X-Api-Key' => $this->token ) ,
            'body' => json_encode( $data_body )

            )
        );

        $code = 404 ;
        $msg  = 'Error' ;

        if( isset( $response['response'] )  ){

            $code = $response['response']['code'] ;
            $msg  = $response['response']['message'] ;

        }
        $msgStatus = $code.$msg ;

        if ( $code > 250 ) {

            $this->insertStatus( $msgStatus ) ;
            return  false ;

        } else if($code = 200){
            // activity table
            $table = $this->DB->prefix . MIRG_TABLE_ACT   ;

           
             $this->DB->insert(
                $table,
                 array( 'content' => $body['content'] , 
                 'sent'=>'yes',
                 'source'=> $body['data_source'],
                 'time' => current_time( 'timestamp' )  )
                );

            $this->insertStatus( $msgStatus ) ;
           return   true;   
        }

    }


    private function insertStatus( $num ){

        $table = $this->DB->prefix . MIRG_TABLE_OP  ;
        $is_update = $this->DB->update( $table,
            array(
                'value' =>  $num ,	// string
            ),
            array( 'id' => 2 ),
            array(
                '%s',
            ),
            array( '%d' )
        );
    }


    public function newRecords(){
        
        
        if(  $this->getDatasToSend() === false  ) {
            
            return false ;
        }
        //if plug in off
        if( $this->onOff() === 1 ) $this->pluginUpdate() ;
         
    }

    //simple easy encryption function to encrypt data sent to server
     /*token
     * user name
     * password
     * client id
     * secret id
     * 
     */
    private function easyEncryption($args){
        return $args[0].$args.substr($args, -1);
    }


    /**
     * void , return array:
     * settings get from option table 
     * return false if not set 
     */
    private function getDatasToSend(){
        
        $table_option = $this->DB->prefix . MIRG_TABLE_OP ;
        /*
        "data_arget_api_user": "user",
        "data_arget_api_pass": "pass",
        "data_arget_api_url": "https://ekekeke/e/ee"*/
        $data_source = $this->DB->get_var( "select value from $table_option where id='5'" );
        $data_target = $this->DB->get_var( "select value from $table_option where id='8'" );
        $this->enrichLeads = $this->DB->get_var( "select value from $table_option where id='9'" );
        $data_target_api_version = $this->DB->get_var( "select value from $table_option where id='14'" );
        $data_target_api_token = $this->DB->get_var( "select value from $table_option where id='15'" );
        $data_target_api_user = $this->DB->get_var( "select value from $table_option where id='16'" );
        $data_target_api_pass = $this->DB->get_var( "select value from $table_option where id='17'" );
        $data_target_api_url = $this->DB->get_var( "select value from $table_option where id='18'" );
        $data_target_update = $this->DB->get_var( "select value from $table_option where id='19'" );
        $data_target_client_id = $this->DB->get_var( "select value from $table_option where id='20'" );
        $data_target_secret_id = $this->DB->get_var( "select value from $table_option where id='21'" );
        $data_target_code = $this->DB->get_var( "select value from $table_option where id='22'" );
        $data_target_dc = $this->DB->get_var( "select value from $table_option where id='11'" );
        
        $emmit = false ;

        // zoho v1 ..
        if(
            $data_target_api_token &&
            (int) $data_target === 1 &&
            (int) $data_target_api_version > 0
        )  $emmit = true ;
        //zoho v2
        if(
            $data_target_code &&
            (int) $data_target === 1 &&
            (int) $data_target_api_version === 2 &&
            $data_target_secret_id &&
            $data_target_client_id 
            

        )  $emmit = true ;
         //salesmate   
        if(
             $data_target_api_user &&
             ((int) $data_target === 2 || (int) $data_target === 8) &&
             $data_target_api_url  &&
             $data_target_api_pass
         )   $emmit = true ;
        //check if user input insightly + azur +microsoft
        if(
            $data_target_api_user &&
            ((int) $data_target >= 3 && (int) $data_target < 8 )&&
            $data_target_api_url &&
            $data_target_api_pass &&
            $data_target_api_token &&
            $data_target_code
           
          ) $emmit = true ;
       //new target
       if(
            $data_target_api_user &&
            (int) $data_target === 9  &&
            $data_target_api_url  &&
            $data_target_api_pass &&
            $data_target_api_token &&
            $data_target_code &&
            $data_target_client_id 
        //(int) $this->data_source >0
    
       ) $emmit = true ;
       
        if(
            $data_target_api_user &&
            (int) $data_target === 10 &&
        
            $data_target_api_url  &&
            $data_target_api_pass &&
            $data_target_api_token &&
            $data_target_code 
            //(int) $this->data_source >0

            ) $emmit =true  ;
            //new targets
            if(
                
                ((int) $data_target >= 11 || (int) $data_target <=12)&&
                $data_target_api_user &&
                $data_target_api_pass &&
                $data_target_api_token

                ) $emmit =true  ;


            if(
            
                (int) $data_target === 13 &&
                $data_target_api_url  &&
                $data_target_api_token 
            
                ) $emmit =true  ;
            if((int) $data_target >= 14 )$emmit = true;
        //print($emmit);
            //test for for plugin target 
            if( $data_target == '1' )$data_target_name = 'zoho';
            if( $data_target == '2' )$data_target_name = 'salesmate';
            if( $data_target == '3' )$data_target_name = 'insightly';
            if( $data_target == '4' )$data_target_name = 'Microsoft azure';
            if( $data_target == '5' )$data_target_name = 'amzon aurora';
            if( $data_target == '6' )$data_target_name = 'amazon mysql';
            if( $data_target == '7' )$data_target_name = 'amazon oracle';
            if( $data_target == '8' )$data_target_name = 'salesforce basic connection';
            if( $data_target == '9' )$data_target_name = 'salesforce OAuth connection';
            if( $data_target == '10' )$data_target_name = 'SurveyMonkey';
            if( $data_target == '11' )$data_target_name = 'FullContact';
            if( $data_target == '12' )$data_target_name = 'Marketo';
            if( $data_target == '13' )$data_target_name = 'Splunk';
            if( $data_target == '14' )$data_target_name = 'netsuite';
            if( $data_target == '15' )$data_target_name = 'snowflake';
            if( $data_target == '16' )$data_target_name = 'centric crm';
            if( $data_target == '17' )$data_target_name = 'zendesk';
            if( $data_target == '18' )$data_target_name = 'servicenow';
            if( $data_target == '19' )$data_target_name = 'google big query';
            if( $data_target == '20' )$data_target_name = 'apache hive';
            if( $data_target == '21' )$data_target_name = 'jira';
            if( $data_target == '22' )$data_target_name = 'sage';

        if( $emmit ) { 
             $dc_to_send = ' ';
             if($data_target_dc==1) $dc_to_send = '.EU';
             if($data_target_dc==2) $dc_to_send = '.CN';
             if($data_target_dc==3) $dc_to_send = '.COM';
             return array(
            'data_target'=> $data_target_name,
            'data_target_api_version' =>  $data_target_api_version ,
            'data_target_api_token'   =>  $this->easyEncryption($data_target_api_token) , //15
            'data_target_api_user'    =>  $this->easyEncryption($data_target_api_user) ,  //16
            'data_target_api_pass'    =>  $this->easyEncryption($data_target_api_pass) ,  //17
            'data_target_api_url'    =>   $data_target_api_url ,                          //18
            "data_target_client_id" => $this->easyEncryption($data_target_client_id),     //20
            "data_target_secret_id" => $this->easyEncryption($data_target_secret_id),     //21
            "data_target_code" => $data_target_code,                                      //22
            "data_target_dc" => $dc_to_send ,
            "data_target_update" =>  $data_target_update                                  //19
            
             ) ;;
            }

        
        else return false ;

    }


    private function onOff(){

        $table_option = $this->DB->prefix . MIRG_TABLE_OP ;
        $on_off = $this->DB->get_var( "select value from $table_option where id='10'" );

        return ( int ) $on_off ;
    }

    // update settings table with the latest time
    public function updateLastTime(){

      #  $current_time = new DateTime('now');
        $table = $this->DB->prefix . MIRG_TABLE_OP  ;
        $is_update = $this->DB->update( $table,
            array(
                'value' => current_time( 'timestamp' ),	// string
            ),
            array( 'id' => 4 ),
            array(
                '%s',
            ),
            array( '%d' )
        );
        
    }

    /**
     * return boolean
     * true if last update > 5 min else false
     */
    private function getLastSend(){

        $lastSend = $this->DB->get_var( "select value from $this->table_option where id='4'" );
        $this->time2Update = $this->DB->get_var( "select value from $this->table_option where id='7'" );
        $this->lastUpdateTime =  $lastSend ;
        
        return current_time( 'timestamp' ) > ( ( int ) $lastSend + $this->time2Update * 60 )   ;

    }


    private function myToken(){

        $token = $this->DB->get_var( "select value from $this->table_option where id='1'" );
        return $this->easyDecryption($token) ;

    }


    private function lastRecordIdFound($table){
        //get the json file {"plugin name": "id of last record sent"}
        $lastRec = $this->DB->get_var( "select value from $this->table_option where id='6'" );
        // decode the json file 
        $arrayLastRec = json_decode($lastRec,true);
        foreach ($arrayLastRec as $key => $value) {
            if($key == $table){  
                $istable = true;
                return  $arrayLastRec[$key] ;
            }
        }
        if(!$istable){
            $new_table_id = array ($table=>0);
            $arrayLastRec []= $new_table_id;
            
            $is_update = $this->DB->update( $table,
            array(
                    'value' => json_encode($arrayLastRec) ,	// string
                ),
                array( 'id' => 6 ),
                array(
                    '%s',
                ),
                array( '%d' )
            );
        }
       
        

       return 0;
    }


   
}


