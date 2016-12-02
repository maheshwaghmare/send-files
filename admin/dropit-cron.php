<?php 

use Dropbox as dbx;

    add_filter( 'cron_schedules', 'dropit_add_cron_intervals' );
    add_action( 'dropit_cron_hook', 'dropit_cron_exec' );        
       
    if(isset($_POST['wp-dropit-basic'])) {
        wp_clear_scheduled_hook('dropit_cron_hook');   
    }

    /*
    *   create schedule for specific time
    */          
    
    function dropit_add_cron_intervals( $schedules ) {
    	    if(isset($_POST['wp-dropit-basic'])) {
		        $settings = (isset($_POST['dropit'])) ? $_POST['dropit'] : array();
		         update_option( 'wp-dropit-basic', $settings );
		    }
            $settings = (get_option( 'wp-dropit-basic' )) ? get_option( 'wp-dropit-basic' ) : array();
            if (!isset($settings['expiry_type'])) {
                $settings['expiry_type'] ='';
            }
            switch ($settings['expiry_type']) {
                    case 'minutes':
                        $expiry = $settings['expiry_number']*60;
                        break;
                    case 'hours':
                        $expiry = $settings['expiry_number']*60*60;
                        break;
                    case 'days':
                        $expiry = $settings['expiry_number']*24*60*60;
                        break;
                    case 'months':
                        $expiry = $settings['expiry_number']*30*24*60*60;
                        break;
                    default:
                        $expiry = 604800;
                        break;
                }($settings['expiry_type']);

            $schedules['specific_time'] = array(
                'interval' => $expiry,
                'display' => __('Specific Time')
            );
    return $schedules;

    }



    /*
    *   schedule event 
    */

    if( !wp_next_scheduled( 'dropit_cron_hook' ) ) {
        wp_schedule_event( time(), 'specific_time', 'dropit_cron_hook' );
    }


    /*
    *   create schedule for deleting file after specific time
    */
    function dropit_cron_exec() {

		$settings = (get_option( 'wp-dropit-basic' )) ? get_option( 'wp-dropit-basic' ) : array(); 
        switch ($settings['expiry_type']) {
            case 'minutes':
                $expiry = $settings['expiry_number']*60;
                break;
            case 'hours':
                $expiry = $settings['expiry_number']*60*60;
                break;
            case 'days':
                $expiry = $settings['expiry_number']*24*60*60;
                break;
            case 'months':
                $expiry = $settings['expiry_number']*30*24*60*60;
                break;
            default:
                $expiry = 604800;
                break;
        }($settings['expiry_type']);

        $database = new DropitDatabase();
        $results = $database->getData();
        $files = $database->getFileData($results->user_id);

        date_default_timezone_set("Asia/Kolkata");
        $curtime = date("Y-m-d H:i:s");
        foreach ($files as $file) {

            if((strtotime($curtime) - strtotime($file->date)) > $expiry) {
                $clientIdentifier = "DropIt/1.0";
                $dbxClient = new dbx\Client($results->access_token, $clientIdentifier);
                try {
                    $dbxClient->delete($file->file);
                }
                catch (dbx\Exception $ex) {
                    echo "something went wrong, please try again</span>";
                }
                $data = array(
                    "user_id" => $results->user_id,
                    "file" => $file->file
                );
                $database->deleteFiles($data);
            }
        }
    }