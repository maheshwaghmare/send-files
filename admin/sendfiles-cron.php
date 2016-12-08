<?php 

use Dropbox as dbx;

    add_filter( 'cron_schedules', 'sendfiles_add_cron_intervals' );
    add_action( 'sendfiles_cron_hook', 'sendfiles_cron_exec' );        
       
    if(isset($_POST['wp-sendfiles-basic'])) {
        wp_clear_scheduled_hook('sendfiles_cron_hook');   
    }

   /**
    *   create schedule for specific time
    */          
    
    function sendfiles_add_cron_intervals( $schedules ) {
            if(isset($_POST['wp-sendfiles-basic'])) {
                $settings = (isset($_POST['sendfiles'])) ? $_POST['sendfiles'] : array();
                 update_option( 'wp-sendfiles-basic', $settings );
            }
            $settings = (get_option( 'wp-sendfiles-basic' )) ? get_option( 'wp-sendfiles-basic' ) : array();
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


   /**
    *   schedule event 
    */

    if( !wp_next_scheduled( 'sendfiles_cron_hook' ) ) {
        wp_schedule_event( time(), 'specific_time', 'sendfiles_cron_hook' );
    }


   /**
    *   create schedule for deleting file after specific time
    */
    function sendfiles_cron_exec() {

        $settings = (get_option( 'wp-sendfiles-basic' )) ? get_option( 'wp-sendfiles-basic' ) : array(); 
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

        $database = new SendfilesDatabase();
        $values = (get_option( 'sendfiles-auth' )) ? get_option( 'sendfiles-auth' ) : array();
        $files = $database->getFileData($values['user_id']);

        date_default_timezone_set("Asia/Kolkata");
        $curtime = date("Y-m-d H:i:s");
        foreach ($files as $file) {


            // get the total time difference between current time and file uploaded time
            if((strtotime($curtime) - strtotime($file->date)) > $expiry) {
                $clientIdentifier = "SendFiles/1.0";
                $dbxClient = new dbx\Client($values['access_token'], $clientIdentifier);
                try {
                    $dbxClient->delete($file->file);
                }
                catch (dbx\Exception $ex) {
                    _e('Something went wrong, please try again','send-files');
                }
                $data = array(
                    "user_id" => $values['user_id'],
                    "file" => $file->file
                );
                $database->deleteFiles($data);
            }
        }
    }