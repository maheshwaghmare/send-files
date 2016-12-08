<?php  

use Dropbox as dbx;



class SendfilesDatabase {


	function __construct() {
		
     }


	/*
	* Create uploaded files track table on activation of plugin
	*/
	public function createTable() {

      	global $wpdb;
		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

		$table_files = $wpdb->prefix . "sendfiles_files"; 
		$sql_sendfiles_files = "CREATE TABLE $table_files (
			id int(11) NOT NULL AUTO_INCREMENT,
			user_id int(11) NULL,
			file varchar(200) NULL,
			date TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
			PRIMARY KEY  (id)
		) $charset_collate;";

		dbDelta( $sql_sendfiles_files );
		
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

	    $table_name = $wpdb->prefix . 'sendfiles_files';
	    $sql = "DROP TABLE IF EXISTS $table_name";
	    $wpdb->query($sql);
	}
}