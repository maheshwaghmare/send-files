<?php
/*
Plugin Name: Send Files
Plugin URI: https://www.brainstormforce.com
Author: Brainstorm Force
Author URI: https://www.brainstormforce.com
Description: Send Files is a simple plugin that connects with your Dropbox account. Once the plugin is installed and configured, you can create a beautiful page on your website where your users can upload files and share the link with you (or anyone else)
Version: 0.0.1
Text Domain: send-files
*/

if(!defined('SENDFILES_VERSION')) {
	define( 'SENDFILES_VERSION', '0.0.1' );
}
if(!defined('SENDFILES_PATH')) {
	define( 'SENDFILES_PATH', plugin_dir_path(__FILE__) );
}

require_once(SENDFILES_PATH.'dropbox/autoload.php');
require_once(SENDFILES_PATH.'classes/upload.interface.php');
require_once(SENDFILES_PATH.'classes/dropbox.class.php');
include_once(SENDFILES_PATH.'admin/sendfiles-cron.php');
use \Dropbox as dbx;

if(!class_exists('WP_SendFiles')) {
	class WP_SendFiles{

	  // Constructor
	    function __construct() {

	        add_action( 'admin_menu', array( $this, 'init_admin_menu' ) );
			add_shortcode( 'sendfiles', array($this, 'sendfilesShortcode') );

			add_action( 'wp_ajax_sendfiles', array($this, 'sendfiles_process') );
			add_action( 'wp_ajax_nopriv_sendfiles', array($this, 'sendfiles_process') );

			add_action( 'wp_ajax_sendfiles_authenticate', array($this, 'sendfiles_authenticate_process') );

			add_action( 'wp_ajax_sendfiles_disconnect', array($this, 'sendfiles_disconnect_process') );

			add_action( 'wp_enqueue_scripts', array($this, 'sendfiles_assets') );
			add_action( 'admin_enqueue_scripts', array($this, 'sendfiles_admin_assets') );

	    }

		/*
		* Actions perform at loading of admin menu
		*/
	    function init_admin_menu() {

	        add_submenu_page(
				'options-general.php',
				__( 'Send Files', 'send-files' ),
				__( 'Send Files', 'send-files' ),
				'manage_options',
				'wp-sendfiles',
				array( $this, 'sendfiles_admin_page' )
				);
	    }

		/*
		* Actions perform on loading of menu pages
		*/
	    function sendfiles_admin_page() {


	    	include_once SENDFILES_PATH.'admin/dashboard.php';
	    	include_once SENDFILES_PATH.'admin/dashboard-options.php';

	    }

		/*
		* Actions perform to add styles & scripts
		*/
		function sendfiles_assets() {

			wp_enqueue_script( 'jquery' );
			wp_register_script( 'sendfiles-js', plugin_dir_url( __FILE__ ).'assets/js/script.js', array('jquery'), SENDFILES_VERSION );

			// Localize the script with new data
			$messages_array = array(
				'copy_clipboard' => __('(Copied to clipboard)' ,'send-files'),
				'copy_clipboard_fail' => __('(Copy to clipboard failed)' ,'send-files'),
				'select_file'=>__('Please select a file to upload' , 'send-files'),
				'error_message'=>__('Something went wrong please try again' , 'send-files'),
			);
			wp_localize_script( 'sendfiles-js', 'sendfiles', $messages_array );

			wp_register_script( 'clipboard-js', plugin_dir_url( __FILE__ ).'assets/js/clipboard.min.js', array('jquery')  );
			wp_enqueue_script( 'sendfiles-js' );
			wp_enqueue_script( 'clipboard-js' );

			wp_register_style( 'sendfiles-css', plugin_dir_url( __FILE__ ).'assets/css/style.css', null, SENDFILES_VERSION );
			wp_enqueue_style( 'sendfiles-css' );

		}

		/*
		* Actions perform to add admin styles & scripts
		*/
		function sendfiles_admin_assets(){

			wp_enqueue_script( 'jquery' );
	        wp_enqueue_style( 'wp-color-picker' );
	        wp_enqueue_style( 'wp-jquery-ui-dialog' );
			wp_register_script( 'admin-sendfiles-js', plugin_dir_url( __FILE__ ).'assets/admin/js/admin-script.js', array('jquery','jquery-ui-dialog'), SENDFILES_VERSION );
			wp_enqueue_script( 'wp-color-picker-alpha', plugin_dir_url( __FILE__ ).'assets/admin/js/wp-color-picker-alpha.min.js', array( 'wp-color-picker' ), SENDFILES_VERSION);
			wp_register_style( 'admin-sendfiles-css', plugin_dir_url( __FILE__ ).'assets/admin/css/admin-style.css', null, SENDFILES_VERSION );

			// Localize the script with new data
			$messages_array = array(
				'add_token' => __('Please add the Token' ,'send-files'),
				'token_expire'=>__('Token doesn\'t exist or has expired, please try to add valid token.' , 'send-files'),
				'auth_success'=>__('Authentication is completed. Please Wait' , 'send-files'),
				'disconnect_success'=>__('Successfully disconnected. Please Wait' , 'send-files'),
				'error_message'=>__('Something went wrong please try again' , 'send-files'),
				'admin_sendfiles_url' => admin_url( 'admin-ajax.php' )
			);
			wp_localize_script( 'admin-sendfiles-js', 'admin_sendfiles', $messages_array );
			wp_enqueue_script( 'admin-sendfiles-js' );
			wp_enqueue_style( 'admin-sendfiles-css' );
		}



		/*
		* Actions perform for Shortcode
		*/
		function sendfilesShortcode( $attr, $content ) {

			// get field value of general options
			$settings = (get_option( 'wp-sendfiles-basic' )) ? get_option( 'wp-sendfiles-basic' ) : array();
			if (isset($settings['border_color']) && $settings['border_color'] !='') 
			    $border_color = $settings['border_color'];
			else
				$border_color = '';
			if (isset($settings['loading_bg_color'])) 
				$loading_bg_color = $settings['loading_bg_color'];	
			else
				$loading_bg_color = '';
			if (isset($settings['file_text']) && $settings['file_text'] !='') 
				$file_text = $settings['file_text'];
			else
				$file_text = __( ' Drop file here or click to upload.', 'send-files' );
			if (isset($settings['upload_font_color'])) 
				$upload_font_color = $settings['upload_font_color'];	
			else
				$upload_font_color = '';
			if (isset($settings['link_title_color'])) 
				$link_title_color = $settings['link_title_color'];	
			else
				$link_title_color = '';
			if (isset($settings['link_bg_color'])) 
				$link_bg_color = $settings['link_bg_color'];
			else
				$link_bg_color = '';
			if (isset($settings['link_title']) && $settings['link_title'] !='') 
				$link_title = $settings['link_title'];
			else
				$link_title = __( 'Share this link with anyone!', 'send-files');
			if (isset($settings['loading_border_color'])) 
				$loading_border_color = $settings['loading_border_color'];
			else
				$loading_border_color = '';
			if (isset($settings['loading_bg_color'])) 
				$loading_bg_color = $settings['loading_bg_color'];
			else
				$loading_bg_color = '';
			
			ob_start();
			?>

			<!-- file upload wrapper -->
			<div class="file-upload-wrapper" style="color:<?php echo esc_attr($upload_font_color);?>">
				<div class="error-message"></div>
					<form id="sendfiles-form" enctype="multipart/form-data" method="POST" action="<?php echo esc_url( admin_url('admin-ajax.php') ); ?>" onSubmit="return false">
						<input type="hidden" name="action" value="sendfiles" />
						<div class="file-drop-area" style="border-color:<?php echo esc_attr($border_color); ?>">
							<span class="file-msg js-set-number" data-choose-title="<?php echo esc_attr($file_text); ?>" ><?php echo esc_attr($file_text); ?></span>
							<input class="file-input" type="file" name="sendfiles-files" id="sendfiles-files">
							<div class="loader" style="border-color:<?php echo esc_attr($loading_bg_color); ?>;border-top-color:<?php echo esc_attr($loading_border_color); ?>"></div>
						</div>
					</form>

				<!-- sharable link wrapper -->
				<div class="shortlink-wrapper" style="background-color:<?php echo esc_attr($link_bg_color); ?>">
					<fieldset>
						<lable style="color:<?php echo esc_attr($link_title_color);?>"><?php echo esc_attr($link_title);?></lable>
						<a class="copy-btn" data-clipboard-target="#shortlink" style="color:<?php echo esc_attr($link_title_color);?>"><?php _e( 'Copy Link', 'sendfiles' );?></a><span class="copy-msg" style="color:<?php echo $link_title_color;?>"></span>
						<input id="shortlink" type="text" size="60" />
					</fieldset>
				</div>

			</div>

			<?php
			$output = ob_get_clean();
			return $output;

		}

		   /**
			* Actions perform to upload file to dropbox
			*/
			function sendfiles_process() {

				$dropbox = new Dropbox();
				$dropbox->uploadFile();

			}


		   /**
			* Actions perform for authentication
			*/
			function sendfiles_authenticate_process() {

				$dropbox = new Dropbox();
		    	$webAuth = $dropbox->getWebAuth();

				try {
		            // get access token and user id
		            list($accessToken, $userId) = $webAuth->finish($_POST['auth_code']);
		        }
				catch (dbx\Exception $ex) {
					echo '0';
					die();
				}
				// get account details 
				$accountInfo = $dropbox->getAccountInfo($accessToken);

				// set account details to database
				$dropbox->setAccessTokenUserDetails($accessToken, $userId, $accountInfo['display_name']);

				echo "1";
				die();
		}

		   /**
			* Actions perform for disconnect from dropbox
			*/
			function sendfiles_disconnect_process() {
				delete_option( 'sendfiles-auth' );
				echo "1";
			}
	}

	new WP_SendFiles();
}
