
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
                        <?php $url = admin_url( 'options-general.php?page=wp-sendfiles'); ?>
                        
                <a href="<?php echo $url.'&tab=welcome' ?>" class="nav-tab <?php echo $active_tab == 'welcome' ? 'nav-tab-active' : ''; ?>"><?php _e( 'Welcome', 'send-files');?></a>
                <a href="<?php echo $url.'&tab=dropbox_options' ?>" class="nav-tab <?php echo $active_tab == 'dropbox_options' ? 'nav-tab-active' : ''; ?>"><?php _e( 'Dropbox Options', 'send-files');?></a>
                <a href="<?php echo $url.'&tab=general_options' ?>" class="nav-tab <?php echo $active_tab == 'general_options' ? 'nav-tab-active' : ''; ?>"><?php _e( 'General Options', 'send-files');?></a>
                <a href="<?php echo $url.'&tab=how_it_works' ?>" class="nav-tab <?php echo $active_tab == 'how_it_works' ? 'nav-tab-active' : ''; ?>"><?php _e( 'How It Works?', 'send-files');?></a>
            </h2>
         </div>

         <!-- welcome tabs -->
        <?php if ($active_tab == 'welcome' ):?>

            <div class="welcome-panel">
                <h1><?php _e( 'Welcome to', 'send-files');?> <span class="version"><?php _e( 'Send Files', 'send-files');?></span></h1>
                <p><?php _e( 'Thank you for choosing sendfiles', 'send-files');?></p>
                <p><?php _e( 'The easiest way to share files using Dropbox.', 'send-files');?>
                   <?php _e( 'It will allow you upload large files on your Dropbox account and will return sharable public URL.', 'send-files');?></p>
                <h3><?php _e( 'Getting Started -', 'send-files');?> <a href="<?php echo $url.'&tab=dropbox_options' ?>"><?php _e( 'Connect with your Dropbox Account.', 'send-files');?></a></h3>
            </div>

        <?php endif;?>


        <!-- dropbox options tabs -->
        <?php 
         if ($active_tab == 'dropbox_options' ):
         ?>
        <?php
            $is_update = false;
            if(isset($_POST['wp-sendfiles-button'])) {

                $settings = (isset($_POST['sendfiles'])) ? sanitize_text_field( $_POST['sendfiles'] ) : array();
                $is_update = update_option( 'wpsendfiles', $settings );
            }
            $settings = get_option( 'wpsendfiles' , array()); 
        ?>

        <div class="welcome-panel">
                <?php 
                $values = get_option( 'sendfiles-auth' , array()); 
                if (isset($values['display_name'])) : ?>
                    <div class='account-info'>
                        <h1>
                            <?php _e( 'Dropbox Account Details', 'send-files') ?>
                            <span class='dashicons dashicons-yes activate'></span>
                            <span class='green'>
                                <?php _e('Connected', 'send-files') ?>
                            </span>
                        </h1>
                        <p>
                            <b><?php _e( 'Name:-', 'send-files') ?>
                                <span class="green">
                                     <?php echo $values['display_name']; ?>
                                 </span>
                            </b>
                        </p>
                    </div>

                    <div class="panel-content">
                        <div class="cart">
                            <p>
                                <b><?php _e( 'Step 1.', 'send-files') ?></b> <!-- Instruction step 1 -->

                                <?php _e( 'Please click below button to connect with another dropbox account and get the Token from pop up window.', 'send-files'); ?>
                            </p>

                            <?php
                            $dropbox = new Dropbox();
                            $dropboxAuthUrl = $dropbox->getAuthUrl();?>

                            <a class="button-primary connect-btn" target="_blank" href="<?php echo $dropboxAuthUrl; ?>" >
                                <?php _e( 'Reconnect With Dropbox', 'send-files') ?>
                            </a>
                            <a class="button-secondary disconnect-btn" >
                                <?php _e( 'Disconnect With Dropbox', 'send-files') ?>
                            </a>
                        <br>

                <?php else : ?>
                    <div class='account-info'>
                        <h1>
                            <?php  _e( 'Dropbox Account Details', 'send-files') ?>
                            <span class='dashicons dashicons-no not-activate'></span>
                            <span class='red'>
                                <?php _e('Not Connected', 'send-files') ?>
                            </span>
                        </h1>
                    </div>

                    <div class='panel-content'>
                    <h3>
                        <?php _e( ' In order to use Send Files you will need to connect with your Dropbox account.', 'send-files') ?>
                    </h3>
                    <br>
                        <p>
                            <b><?php _e( 'Step 1.</b> Please click the [Connect With Dropbox] button below and get the Token from pop up window.', 'send-files') ?></b>
                        </p>

                    <?php $dropbox = new Dropbox();
                    $dropboxAuthUrl = $dropbox->getAuthUrl();?>
                    <a class="button-primary connect-btn" target="_blank" href="<?php echo $dropboxAuthUrl; ?>">
                        <?php _e( 'Connect With Dropbox', 'send-files') ?>
                    </a><br>

                <?php endif; ?>
                    <br>
                        <div class="token-wrapper">
                            <p>
                                <b> <?php _e( 'Step 2.', 'send-files');?></b> <!-- Instruction step 2 -->

                                <?php _e( 'Add Token and save settings', 'send-files'); ?>
                            </p>

                        <form method="post" class="token-form" name="token-form">
                            <table id="createuser" class="form-table">
                                <tr class="form-field">
                                    <td>
                                        <label><?php  _e( 'Token', 'send-files' ); ?></label>
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
    