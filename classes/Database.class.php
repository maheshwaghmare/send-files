<?php  

use Dropbox as dbx;



class SendfilesDatabase {


	function __construct() {
		
     }


	/*
	* Create tables on activation of plugin
	*/
	public function createTable() {

      	global $wpdb;
		// creates my_table in database if not exists
		$table = $wpdb->prefix . "sendfiles"; 
		$charset_collate = $wpdb->get_charset_collate();
		$sql_sendfiles = "CREATE TABLE $table (
			id int(11) DEFAULT '1',
			auth_code text NULL,
			access_token text NULL,
			email_verified tinyint(1) NULL,
			name varchar(50) NULL,
			user_id int(11) NULL,
			email varchar(50) NULL,
			referral_link text NULL,
			oauth_state varchar(20) DEFAULT 'request',
			user_state varchar(50) DEFAULT 'request'
		) $charset_collate;";

		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		dbDelta( $sql_sendfiles);


		$table_files = $wpdb->prefix . "sendfiles_files"; 
		$sql_sendfiles_files = "CREATE TABLE $table_files (
			id int(11) NOT NULL AUTO_INCREMENT,
			user_id int(11) NULL,
			file varchar(200) NULL,
			date TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
			PRIMARY KEY  (id)
		) $charset_collate;";

		dbDelta( $sql_sendfiles_files );
		
		$wpdb->insert( 
			$table, 
			array( 
				'id' => '1'
			)
		);
    } 


	/*
	* insert data to wpsendfiles table
	*/
    public function insertData($data) {
		global $wpdb;
		$tablename=$wpdb->prefix.'sendfiles';
		//access code and user id
		if (isset($data['userId'])) {

					$result = $wpdb->update(
					$tablename,
						array( 
		                    "access_token" => $data['accessToken'],
		                    "user_id" => $data['userId'],
		                    "oauth_state" => 'access',
		                    "referral_link" => $data['referral_link'],
			                 "name" => $data['display_name'],
		                	 "email_verified" =>$data['email_verified'],
		                	 "email" => $data['email'],
		                     'user_state' =>'access'
						), 
						array(
							"id" => 1
						) 
					);	
		}
		else{
			$result = $wpdb->update(
					$tablename,
						array( 
		                    "access_token" => $data['accessToken'],
		                    "user_id" => $data['userId'],
		                    "oauth_state" => 'request',
		                    "referral_link" => $data['referral_link'],
			                 "name" => $data['display_name'],
		                	 "email_verified" =>$data['email_verified'],
		                	 "email" => $data['email'],
		                     'user_state' =>'request'
						), 
						array(
							"id" => 1
						)
					); 
		}
		return $result;
               	
    }


	/*
	* get data from wpsendfiles table
	*/
    public function getData()
    {
    	global $wpdb;
    	$table_name = $wpdb->prefix . 'sendfiles';    
    	$results = $wpdb->get_row( "SELECT * FROM $table_name" );
    	return $results;

    }



	/*
	* get data from sendfiles_files table
	*/
    public function getFileData($user_id)
    {
    	global $wpdb;
    	$table_files = $wpdb->prefix . 'sendfiles_files';    
    	$results = $wpdb->get_results("SELECT * FROM $table_files WHERE user_id = $user_id ;");
    	return $results;
    	 

    }

	/*
	* insert data to sendfiles_files table
	*/
	public function insertFiles($data)
	{
		global $wpdb;
		$table_files = $wpdb->prefix . "sendfiles_files"; 
		$result = $wpdb->insert(
			$table_files,
				array( 
                  "user_id" => $data['user_id'],
                  "file" => $data['filename']
				)
			);
	}



	/*
	* delete file from sendfiles_files table
	*/
	public function deleteFiles($data)
	{
		global $wpdb;
		$table_files = $wpdb->prefix . "sendfiles_files"; 
		  $result = $wpdb->delete(
			$table_files,
				array( 
                    "file" => $data['file'],
                    "user_id" => $data['user_id']
				)
			);
	}
		   
	/*
	* Drop tables on deactivation of plugin
	*/
	public function dropTable() {	

		global $wpdb;
	    $table_name = $wpdb->prefix . 'sendfiles';
	    $sql = "DROP TABLE IF EXISTS $table_name";
	    $wpdb->query($sql);

	    $table_name = $wpdb->prefix . 'sendfiles_files';
	    $sql = "DROP TABLE IF EXISTS $table_name";
	    $wpdb->query($sql);
	}
}