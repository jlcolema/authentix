<?php
/**
 * Plugin Name: WPCF7 Salesforce Web To Lead
 * Description: Integrates web to lead form with the contact form 7.
 * Version: 1.0
 * Author: Chanchal Vashistha@iPragmatech Solutions
 * Author URI: http://www.ipragmatech.com
*/

if(!class_exists('WPCF_Salesforce'))
{
    class WPCF_Salesforce
    {
    	var $webservice_error = '';
    	
        /**
         * Construct the plugin object
         */
        public function __construct()
        {
        	add_action('wpcf7_before_send_mail', array(&$this, 'web_to_lead_integration'));
        	add_filter( 'wpcf7_ajax_json_echo', array(&$this, 'insert_webservice_output'));
        } // END public function __construct

        /**
         * Activate the plugin
         */
        public static function activate()
        {
        	if ( ! is_plugin_active( 'contact-form-7/wp-contact-form-7.php' ) ) {
           		wp_die('Sorry, but this plugin requires Contact Form 7 plugin  to be installed and active. <br><a href="' . admin_url( 'plugins.php' ) . '">&laquo; Return to Plugins</a>');
        	}
        } // END public static function activate

        /**
         * Deactivate the plugin
         */     
        public static function deactivate()
        {
            // Do nothing
        } // END public static function deactivate
        
        /**
         * Insert Webservice Output
         */
        function insert_webservice_output($items) {
        	 
        	if (!empty($this->webservice_error)) {
        		$items['message'] = $this->webservice_error;
        	}
      
        	return $items;
        }
              
        /**
         * Web To Lead Salesforce Integration
         */
        public function web_to_lead_integration($cf7){
			$additional_settings = $cf7->prop('additional_settings');
	        foreach(preg_split("/((\r?\n)|(\r\n?))/", $additional_settings) as $line){
			    // do stuff with $line
	        	if (strpos($line,'web_to_lead') !== false) {	
	        		$matches = explode(':', $line);
	        		$matches = explode("=", $matches[1]);
	        		$params[$matches[0]] = $matches[1];
	        	}
			} 
			
        	$fes = wpcf7_scan_shortcode();
     
        	foreach ( $fes as $fe ) {
        		if ( ! isset( $fe['name'] ) || ! isset( $_POST[$fe['name']] ) )
        			continue;
        	
        		$value = $_POST[$fe['name']];
     
        		if ( is_array( $value ) )
        			$value = implode( ', ', wpcf7_array_flatten( $value ) );
        	
        		$value = trim( $value );
        	
				$options = (array) $fe['options'];
			
				$web_to_lead = preg_grep( '%^web_to_lead:%', $options );
						
        		if ( $web_to_lead ) {
        			foreach ($web_to_lead as $web) {
        				$label_name = explode( ":", $web);
        				$params[$label_name[1]] = $value;
        			}
        		}
        	}
        	
        	$params = array_filter( (array)$params );
        	
        	if ( ! $params )
        		return false;
        	        	
        	$this->submit_form($params);
        }
        
        /**
         * Submit Web To lead Contact Form
         */
        public function submit_form($params){
        	$params['debug'] = 1;
        	$postdata = http_build_query($params);
        	
        	try {
        		$opts = array(
        				'http' => array(
        						'method'  => 'POST',
        						'header'  => 'Content-type: application/x-www-form-urlencoded',
        						'content' => $postdata
        				));
        		$context  = stream_context_create($opts);
        		$result = file_get_contents('https://www.salesforce.com/servlet/servlet.WebToLead?encoding=UTF-8', false, $context);
        		
        		error_log(print_r($result,true));
        		
        		if (strpos($result,'Your request has been queued') !== false) {
        			$this->webservice_error = 'Your request has been queued.';
        		}
        		else{
        			$this->webservice_error = 'Error: We could not create this lead. Please try again later';
        		}
        		
        		if (isset($_GET['debug']) && $_GET['debug'] == $params['debugError']) $this->webservice_error = $result;
        		
        	} catch (Exception $e) {
        		//error_log("Exception Error : ".print_r($e, true));
        		$this->webservice_error = 'Error: Not able to connect to our salesforce web services. Please try later.';
        		if (isset($_GET['debug']) && $_GET['debug'] == $params['debugError']) $this->webservice_error = $e;
        	}
        }
    } // END class WPCF_Salesforce
}  // END if(!class_exists('WPCF_Salesforce'))

if(class_exists('WPCF_Salesforce'))
{
	// Installation and uninstallation hooks
	register_activation_hook(__FILE__, array('WPCF_Salesforce', 'activate'));
	register_deactivation_hook(__FILE__, array('WPCF_Salesforce', 'deactivate'));

	// instantiate the plugin class
	$WPCF_Salesforce = new WPCF_Salesforce();
}
