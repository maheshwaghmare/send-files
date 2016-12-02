<?php
/*
Plugin Name: Send Files
Plugin URI: https://www.brainstormforce.com
Author: BrainStrom Force
Author URI: https://www.brainstormforce.com
Description: Send Files is a simple plugin that connects with your Dropbox account. Once the plugin is installed and configured, you can create a beautiful page on your website where your users can upload files and share the link with you (or anyone else)
Version: 0.0.1
Text Domain: sendfiles
*/

if(!defined('DROPIT_VERSION')) {
	define( 'DROPIT_VERSION', '0.0.1' );
}
if(!defined('DROPIT_PATH')) {
	define( 'DROPIT_PATH', plugin_dir_path(__FILE__) );
}

require_once(DROPIT_PATH.'dropbox/autoload.php');
require_once(DROPIT_PATH.'classes/Dropbox.class.php');
require_once(DROPIT_PATH.'classes/Database.class.php');
include_once(DROPIT_PATH.'admin/dropit-cron.php');

ini_set('max_execution_time', 300);

use \Dropbox as dbx;

if(!class_exists('WP_DropIt')) {
	class WP_DropIt{

	  // Constructor
	    function __construct() {

	        register_activation_hook( __FILE__, array( $this, 'wpa_install' ) );
	        register_deactivation_hook( __FILE__, array( $this, 'wpa_uninstall' ) );

	        add_action( 'admin_menu', array( $this, 'init_admin_menu' ) );
			add_shortcode( 'wpdropit', array($this, 'dropit_shortcode') );

			add_action( 'wp_ajax_dropit', array($this, 'dropit_process') );
			add_action( 'wp_ajax_nopriv_dropit', array($this, 'dropit_process') );

			add_action( 'wp_ajax_dropit_authenticate', array($this, 'dropit_authenticate_process') );
			add_action( 'wp_ajax_nopriv_dropit_authenticate', array($this, 'dropit_authenticate_process') );

			add_action( 'wp_ajax_dropit_disconnect', array($this, 'dropit_disconnect_process') );
			add_action( 'wp_ajax_nopriv_dropit_disconnect', array($this, 'dropit_disconnect_process') );

			add_action( 'wp_enqueue_scripts', array($this, 'dropit_assets') );
			add_action( 'admin_enqueue_scripts', array($this, 'dropit_admin_assets') );

	    }

		/*
		* Actions perform at loading of admin menu
		*/
	    function init_admin_menu() {

	        add_submenu_page(
				'options-general.php',
				__( 'DropIt', 'sendfiles' ),
				__( 'DropIt', 'sendfiles' ),
				'manage_options',
				'wp-dropit',
				array( $this, 'dropit_admin_page' )
				);
	    }

		/*
		* Actions perform on loading of menu pages
		*/
	    function dropit_admin_page() {


	    	include_once DROPIT_PATH.'admin/dashboard.php';
	    	include_once DROPIT_PATH.'admin/dashboard-options.php';

	    }

		/*
		* Actions perform on activation of plugin
		*/
	    function wpa_install() {

	    	// create table
	    	 $database = new DropitDatabase();
	    	 $database->createTable();


	    }

		/*
		* Actions perform on de-activation of plugin
		*/
	    function wpa_uninstall() {

	    	// drop table
			$database = new DropitDatabase();
			$database->dropTable();

	    }

		/*
		* Actions perform to add styles & scripts
		*/
		function dropit_assets() {

			wp_enqueue_script( 'jquery' );
			wp_register_script( 'dropit-js', plugin_dir_url( __FILE__ ).'assets/js/script.js', array('jquery'), DROPIT_VERSION );
			wp_register_script( 'clipboard-js', plugin_dir_url( __FILE__ ).'assets/js/clipboard.min.js', array('jquery')  );
			wp_enqueue_script( 'dropit-js' );
			wp_enqueue_script( 'clipboard-js' );

			wp_register_style( 'dropit-css', plugin_dir_url( __FILE__ ).'assets/css/style.css', null, DROPIT_VERSION );
			wp_enqueue_style( 'dropit-css' );

		}

		/*
		* Actions perform to add admin styles & scripts
		*/
		function dropit_admin_assets(){

			wp_enqueue_script( 'jquery' );
	        wp_enqueue_style( 'wp-color-picker' );
	        wp_enqueue_style( 'wp-jquery-ui-dialog' );
			wp_register_script( 'admin-dropit-js', plugin_dir_url( __FILE__ ).'assets/admin/js/admin-script.js', array('jquery','jquery-ui-dialog'), DROPIT_VERSION );
			wp_enqueue_script( 'wp-color-picker-alpha', plugin_dir_url( __FILE__ ).'assets/admin/js/wp-color-picker-alpha.min.js', array( 'wp-color-picker' ), DROPIT_VERSION);
			wp_register_style( 'admin-dropit-css', plugin_dir_url( __FILE__ ).'assets/admin/css/admin-style.css', null, DROPIT_VERSION );

			// Localize the script with new data
			// $messages_array = array(
			// 	'some_string' => __( 'Some string to translate', 'plugin-domain' ),
			// 	'a_value' => '10'
			// );
			// wp_localize_script( 'admin-dropit-js', 'admin_dropit', $translation_array );


			wp_localize_script( 'admin-dropit-js', 'admin_dropit', array(
				'admin_dropit_url' => admin_url( 'admin-ajax.php' )
			));
			wp_enqueue_script( 'admin-dropit-js' );
			wp_enqueue_style( 'admin-dropit-css' );
		}



		/*
		* Actions perform for Shortcode
		*/
		function dropit_shortcode( $attr, $content ) {

			
			$settings = (get_option( 'wp-dropit-basic' )) ? get_option( 'wp-dropit-basic' ) : array();
			if (isset($settings['border_color'])) {
			    $border_color = $settings['border_color'];
			}
			if (isset($settings['loading_bg_color'])) {
				$loading_bg_color = $settings['loading_bg_color'];	
			}
			if (isset($settings['file_text']) && $settings['file_text'] !='') {
				$file_text = $settings['file_text'];
			}
			else{
				$file_text = __( ' Drop file here or click to upload.', 'sendfiles' );
			}
			if (isset($settings['upload_font_color'])) {
				$upload_font_color = $settings['upload_font_color'];	
			}
			if (isset($settings['link_title_color'])) {
				$link_title_color = $settings['link_title_color'];	
			}
			if (isset($settings['link_bg_color'])) {
				$link_bg_color = $settings['link_bg_color'];
			}
			if (isset($settings['link_title']) && $settings['link_title'] !='') {
				$link_title = $settings['link_title'];
			}
			else{
				$link_title = __( 'Share this link with anyone!', 'sendfiles');
			}
			
			ob_start();
			?>

			<!-- file upload wrapper -->
			<div class="file-upload-wrapper" style="color:<?php echo esc_attr($upload_font_color);?>">
				<div class="error-message"></div>
					<form id="dropit-form" enctype="multipart/form-data" method="POST" action="<?php echo esc_url( admin_url('admin-ajax.php') ); ?>" onSubmit="return false">
						<input type="hidden" name="action" value="dropit" />
						<div class="file-drop-area" style="border-color:<?php echo esc_attr($border_color); ?>">
							<span class="file-msg js-set-number"><span><?php echo esc_attr($file_text); ?></span></span>
							<input class="file-input" type="file" name="dropit-files" id="dropit-files">
						</div>

						<!-- file upload loader -->
						<div class="loader">
							<div class="spinner">
							  <div class="bounce1" style="background-color:<?php echo esc_attr($loading_bg_color); ?>" ></div>
							  <div class="bounce2" style="background-color:<?php echo esc_attr($loading_bg_color); ?>" ></div>
							  <div class="bounce3" style="background-color:<?php echo esc_attr($loading_bg_color); ?>" ></div>
							  <div class="bounce4" style="background-color:<?php echo esc_attr($loading_bg_color); ?>" ></div>
							  <div class="bounce5" style="background-color:<?php echo esc_attr($loading_bg_color); ?>" ></div>
							</div>
						</div>

					</form>

				<!-- sharable link wrapper -->
				<div class="shortlink-wrapper" style="background-color:<?php echo esc_attr($link_bg_color); ?>">
					<fieldset>
						<lable style="color:<?php echo esc_attr($link_title_color);?>"><?php echo esc_attr($link_title);?></lable>
						<a class="copy-btn" data-clipboard-target="#shortlink" style="color:<?php echo esc_attr($link_title_color);?>"><?php _e( '(Copied to clipboard)', 'sendfiles' );?></a><span class="copy-msg" style="color:<?php echo $link_title_color;?>"></span>
						<input id="shortlink" type="text" size="60" />
					</fieldset>
				</div>

			</div>

			<?php
			$output = ob_get_clean();
			return $output;

		}

			/*
			* Actions perform to upload image to dropbox
			*/
			function dropit_process() {

				$database = new DropitDatabase();
				$results = $database->getData();
				$clientIdentifier = "SendFiles/1.0";
				$dbxClient = new dbx\Client($results->access_token, $clientIdentifier);
				$name = $_FILES["dropit-files"]["name"];
				$f = fopen($_FILES["dropit-files"]["tmp_name"], "rb");
				$result = $dbxClient->uploadFile("/".$name, dbx\WriteMode::add(), $f);
				fclose($f);
				$file = $dbxClient->getMetadata('/'.$name);

				// insert uploaded file and time into database
				$data = array('user_id' => $results->user_id ,'filename'=> $result['path']);
				$database->insertFiles($data);

				$dropboxPath = $file['path'];
				$pathError = dbx\Path::findError($dropboxPath);
				if ($pathError !== null) {
					fwrite(STDERR, "Invalid <dropbox-path>: $pathError\n");
					die;
				}

				$link = $dbxClient->createTemporaryDirectLink($dropboxPath);
				$dw_link = $link[0];
				echo $dw_link;//return the uploaded file url
				die();
			}


			/*
			* Actions perform for authentication
			*/
			function dropit_authenticate_process() {
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

				$client = $dropbox->getClient($accessToken);
			    $database = new DropitDatabase();

	            $data = array(
	            	"accessToken" => $accessToken,
	            	 "userId" => $userId,
	            	 "referral_link" => $client['referral_link'],
	            	 "display_name" => $client['display_name'],
	            	 "email_verified" =>$client['email_verified'],
	            	 "email" => $client['email']
	            	);
				$insert =  $database->insertData($data);
				echo "1";
				die();
		}

			/*
			* Actions perform for authentication
			*/
			function dropit_disconnect_process() {
				$database = new DropitDatabase();
	            $data = array(
	            	"accessToken" => null,
	            	 "userId" => null,
	            	 "referral_link" =>null,
	            	 "display_name" => null,
	            	 "email_verified" =>null,
	            	 "email" => null
	            	);
				$insert =  $database->insertData($data);
				echo "1";
			}
}

	new WP_DropIt();
}
