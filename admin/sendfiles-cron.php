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
                $settings = (isset($_POST['sendfiles'])) ? (array_map( 'sanitize_text_field', $_POST['sendfiles'] )) : array();
                 update_option( 'wp-sendfiles-basic', $settings );
            }
            $settings = get_option( 'wp-sendfiles-basic', array());
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

        $settings = get_option( 'wp-sendfiles-basic' , array()); 
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

        $values = get_option( 'sendfiles-auth', array());
        $user_id = $values['user_id'];
        $curtime = date("Y-m-d H:i:s");

        $args = array(
            'post_type' => 'sendfiles_list', 
            's'          => $user_id
                );        
             // get files list to perform the delete operation from server and database also
            $sendfiles_post = new WP_Query($args);
            if($sendfiles_post->have_posts()) :  while($sendfiles_post->have_posts()) : $sendfiles_post->the_post();
 
                         $post_time =  get_the_date('Y-m-d H:i:s');
                        // get the total time difference between current time and file uploaded time
                        if((strtotime($curtime) - strtotime($post_time)) > $expiry) {
                            $clientIdentifier = "SendFiles/1.0";
                            $dbxClient = new dbx\Client($values['access_token'], $clientIdentifier);
                            try {
                                // delete file from server
                                $dbxClient->delete(get_the_content());
                            }
                            catch (dbx\Exception $ex) {
                                _e('Something went wrong, please try again','send-files');
                            }
                            // delete the post from post table also
                            wp_delete_post(get_the_id());
                        // }
                    }

                  endwhile;
               else: 
                    //no files to delete
    endif;
}