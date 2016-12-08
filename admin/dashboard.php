
<!-- dashboard -->
    <div class="wrap wp-sendfiles">
      <h1><?php _e( 'Send Files Settings', 'send-files');?></h1>
         
        <?php
            if( isset( $_GET[ 'tab' ] ) ) {
                $active_tab = $_GET[ 'tab' ];
            }
             $active_tab = isset( $_GET[ 'tab' ] ) ? $_GET[ 'tab' ] : 'welcome';
        ?>
         
         <!-- tabs -->
         <div class="sendfiles-settings-nav">
            <h2 class="nav-tab-wrapper">
                <a href="?page=wp-sendfiles&tab=welcome" class="nav-tab <?php echo $active_tab == 'welcome' ? 'nav-tab-active' : ''; ?>"><?php _e( 'Welcome', 'send-files');?></a>
                <a href="?page=wp-sendfiles&tab=dropbox_options" class="nav-tab <?php echo $active_tab == 'dropbox_options' ? 'nav-tab-active' : ''; ?>"><?php _e( 'Dropbox Options', 'send-files');?></a>
                <a href="?page=wp-sendfiles&tab=gdrive_options" class="nav-tab <?php echo $active_tab == 'gdrive_options' ? 'nav-tab-active' : ''; ?>"><?php _e( 'Google Drive Options', 'send-files');?></a>
                <a href="?page=wp-sendfiles&tab=general_options" class="nav-tab <?php echo $active_tab == 'general_options' ? 'nav-tab-active' : ''; ?>"><?php _e( 'General Options', 'send-files');?></a>
                <a href="?page=wp-sendfiles&tab=how_it_works" class="nav-tab <?php echo $active_tab == 'how_it_works' ? 'nav-tab-active' : ''; ?>"><?php _e( 'How It Works?', 'send-files');?></a>
            </h2>
         </div>

         <!-- welcome tabs -->
        <?php if ($active_tab == 'welcome' ):?>

            <div class="welcome-panel">
                <h1><?php _e( 'Welcome to', 'send-files');?> <span class="version"><?php _e( 'Send Files', 'send-files');?></span></h1>
                <p><?php _e( 'Thank you for choosing sendfiles', 'send-files');?></p>
                <p><?php _e( 'The easiest way to share files using Dropbox.', 'send-files');?>
                   <?php _e( 'It will allow you upload large files on your Dropbox account and will return sharable public URL.', 'send-files');?></p>
                <h3><?php _e( 'Getting Started -', 'send-files');?> <a href="?page=wp-sendfiles&tab=dropbox_options"><?php _e( 'Connect with your Dropbox Account.', 'send-files');?></a></h3>
            </div>

        <?php endif;?>


        <!-- dropbox options tabs -->
        <?php 
         if ($active_tab == 'dropbox_options' ):
         ?>
        <?php
            $is_update = false;
            if(isset($_POST['wp-sendfiles-button'])) {
                $settings = (isset($_POST['sendfiles'])) ? $_POST['sendfiles'] : array();
                $is_update = update_option( 'wpsendfiles', $settings );
            }
            $settings = (get_option( 'wpsendfiles' )) ? get_option( 'wpsendfiles' ) : array(); 
        ?>

        <div class="welcome-panel">
                <?php 
                $values = (get_option( 'sendfiles-auth' )) ? get_option( 'sendfiles-auth' ) : array(); 
                if (isset($values['display_name'])) : 
                    echo "<div class='account-info'><h1>". __( 'Dropbox Account Details', 'send-files')."<span class='dashicons dashicons-yes activate'></span><span class='green'>".__('Connected', 'send-files')."</span></h1>";
                    echo '<p><b>'.__( 'Name:-', 'send-files').'<span class="green">'.$values['display_name'].'</span></b></p></div>';
                    ?>

                <div class="panel-content">
                    <div class="cart">
                        <?php
                        echo '<p><b>'.__( 'Step 1.', 'send-files').'</b>'.__( 'Please click below button to connect with another dropbox account and get the Token from pop up window.', 'send-files').'</p>';

                        $dropbox = new Dropbox();
                        $dropboxAuthUrl = $dropbox->getAuthUrl();

                        echo '<a class="button-primary connect-btn" target="_blank" href='.$dropboxAuthUrl.'>'.__( 'Reconnect With Dropbox', 'send-files').'</a>';
                        echo '<a class="button-secondary disconnect-btn" >'.__( 'Disconnect With Dropbox', 'send-files').'</a><br>';

                else :
                    echo "<div class='account-info'><h1>". __( 'Dropbox Account Details', 'send-files')."<span class='dashicons dashicons-no not-activate'></span><span class='red'>".__('Not Connected', 'send-files')."</span></h1></div>";
                    echo "<div class='panel-content'>";
                    echo '<h3>'.__( ' In order to use Send Files you will need to connect with your Dropbox account.', 'send-files').'</h3><br>';
                    echo '<p><b>'.__( 'Step 1.</b> Please click the [Connect With Dropbox] button below and get the Token from pop up window.', 'send-files').'</p>';
                    $dropbox = new Dropbox();
                    $dropboxAuthUrl = $dropbox->getAuthUrl();
                    echo '<a class="button-primary connect-btn" target="_blank" href='.$dropboxAuthUrl.'>'.__( 'Connect With Dropbox', 'send-files').'</a>';
                endif; ?>
                    <br>
                    <br>
                        <div class="token-wrapper">
                            <p><b><?php _e( 'Step 2.', 'send-files');?></b> 
                                <?php _e( 'Add Token and save settings', 'send-files');?>
                            </p>


                        <form method="post" class="token-form" name="token-form">
                            <table id="createuser" class="form-table">
                                <tr class="form-field">
                                    <td>
                                        <label><?php echo __( 'Token', 'send-files' ); ?></label>
                                    </td>
                                    <td>
                                        <input type="text" id="auth-token" required  placeHolder="<?php _e( 'Please enter the token', 'send-files' ); ?>"/>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2">
                                        <div class="submit-wrapper">
                                            <input type="button" id="authorize-btn"   name="wp-sendfiles-button" class="button-primary" value="<?php echo __( 'Save Settings', 'send-files' ); ?>" />
                                            <div id="ajaxloader"></div>
                                        </div>
                                    </td>
                                </tr>
                            </table>
                        </form>
                    </div>
                </div>
                    <span class="auth-message success"></span>
                    <span class="auth-message error"></span>
                    <!-- disconnected modal message -->
                    <div id="dialog-confirm" title="Disconnect with dropbox?">
                    <p><span class="ui-icon ui-icon-alert" style="float:left; margin:12px 12px 20px 0;"></span><?php _e( 'Do you really want to disconnect with Dropbox?', 'send-files');?></p>
                    </div>
                </div>
            </div>

        <?php endif; ?>
    

     <!-- google drive options tabs -->
        <?php 
         if ($active_tab == 'gdrive_options' ):
         ?>
        <?php
            $is_update = false;
            if(isset($_POST['wp-sendfiles-gdrive'])) {
                $settings = (isset($_POST['sendfiles'])) ? $_POST['sendfiles'] : array();
                $is_update = update_option( 'wpsendfiles', $settings );
            }
            $settings = (get_option( 'wpsendfiles' )) ? get_option( 'wpsendfiles' ) : array(); 
        ?>

        <div class="welcome-panel">
                <?php 
                    echo "<div class='account-info'><h1>". __( 'Google Drive Account Details', 'sendfiles')."<span class='dashicons dashicons-yes activate'></span><span class='green'>".__('Connected', 'sendfiles')."</span></h1>";
                    // ?>
                    <hr>

                <?php   
define('APPLICATION_NAME', 'Project Default Service Account');
define('CLIENT_SECRET_PATH', SENDFILES_PATH.'admin/client_secret.json');
define('SCOPES', implode(' ', array(
    Google_Service_Drive::DRIVE_METADATA)
));
$client = new Google_Client();
// $client->setHttpClient(new GuzzleHttp\Client(['verify' => false]));
$client->setApplicationName(APPLICATION_NAME);
$client->setScopes(SCOPES);
$client->setAuthConfigFile(CLIENT_SECRET_PATH);
// $client->setRedirectUri('https://localhost/wordpress/wp-admin/options-general.php?page=wp-sendfiles');
$client->setAccessType('online');

        // echo '<a href='.$drive_authUrl.'> Click Here </a>';
        
    echo '<a target="_self" href="'. $client->createAuthUrl() . '">Click here to Authenticate</a>';
?>
                        <!-- <div class="token-wrapper"> -->
                        <div >


                            <p><b><?php _e( 'Step 2.', 'sendfiles');?></b> 
                                <?php _e( 'Add Token and save settings', 'sendfiles');?>
                            </p>


                        <form method="post" class="token-form" name="token-form">
                            <table id="createuser" class="form-table">
                                <tr class="form-field">
                                    <td>
                                        <label><?php echo __( 'Token', 'sendfiles' ); ?></label>
                                    </td>
                                    <td>
                                        <input type="text" id="auth-token-gdrive" required  placeHolder="please enter the token"/>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2">
                                        <div class="submit-wrapper">
                                            <input type="button" id="authorize-btn-gdrive"   name="wp-sendfiles-gdrive" class="button-primary" value="<?php echo __( 'Save Settings', 'sendfiles' ); ?>" />
                                            <div id="ajaxloader"></div>
                                        </div>
                                    </td>
                                </tr>
                            </table>
                        </form>
                    </div>
                </div>
                    <br><span class="auth-message success"></span>
                    <br><span class="auth-message error"></span>
                    <!-- disconnected modal message -->
                    <div id="dialog-confirm" title="Disconnect with dropbox?">
                    <p><span class="ui-icon ui-icon-alert" style="float:left; margin:12px 12px 20px 0;"></span><?php _e( 'Do you really want to disconnect with Dropbox?', 'sendfiles');?></p>
                    </div>
                </div>
            </div>

        <?php endif; ?>