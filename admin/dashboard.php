
<!-- dashboard -->
    <div class="wrap wp-dropit">
      <h1><?php _e( 'SendFiles Settings', 'sendfiles');?></h1>
         
        <?php
            if( isset( $_GET[ 'tab' ] ) ) {
                $active_tab = $_GET[ 'tab' ];
            }
             $active_tab = isset( $_GET[ 'tab' ] ) ? $_GET[ 'tab' ] : 'welcome';
        ?>
         
         <!-- tabs -->
         <div class="dropit-settings-nav">
            <h2 class="nav-tab-wrapper">
                <a href="?page=wp-dropit&tab=welcome" class="nav-tab <?php echo $active_tab == 'welcome' ? 'nav-tab-active' : ''; ?>"><?php _e( 'Welcome', 'sendfiles');?></a>
                <a href="?page=wp-dropit&tab=dropbox_options" class="nav-tab <?php echo $active_tab == 'dropbox_options' ? 'nav-tab-active' : ''; ?>"><?php _e( 'Dropbox Options', 'sendfiles');?></a>
                <a href="?page=wp-dropit&tab=general_options" class="nav-tab <?php echo $active_tab == 'general_options' ? 'nav-tab-active' : ''; ?>"><?php _e( 'General Options', 'sendfiles');?></a>
                <a href="?page=wp-dropit&tab=how_it_works" class="nav-tab <?php echo $active_tab == 'how_it_works' ? 'nav-tab-active' : ''; ?>"><?php _e( 'How It Works?', 'sendfiles');?></a>
            </h2>
         </div>


         <!-- welcome tabs -->
        <?php if ($active_tab == 'welcome' ):?>

            <div class="welcome-panel">
                <h1><?php _e( 'Welcome to', 'sendfiles');?> <span class="version"><?php _e( 'Send Files', 'sendfiles');?></span></h1>
                <?php _e( 'Thank you for choosing DropIt', 'sendfiles');?>
                <h4><?php _e( 'The easiest way to share files using Dropbox.', 'sendfiles');?></h4>
                <h4><?php _e( 'It will allow you upload large files on your Dropbox account and will return sharable public URL.', 'sendfiles');?></h4>            <br>
                <h3><?php _e( 'Getting Started -', 'sendfiles');?> <a href="?page=wp-dropit&tab=dropbox_options"><?php _e( 'Connect with your Dropbox Account.', 'sendfiles');?></a></h3>
            </div>

        <?php endif;?>


        <!-- dropbox options tabs -->
        <?php 
         if ($active_tab == 'dropbox_options' ):
         ?>
        <?php
            $is_update = false;
            if(isset($_POST['wp-dropit-button'])) {
                $settings = (isset($_POST['dropit'])) ? $_POST['dropit'] : array();
                $is_update = update_option( 'wpdropit', $settings );
            }
            $settings = (get_option( 'wpdropit' )) ? get_option( 'wpdropit' ) : array(); 
        ?>

        <div class="welcome-panel">
                <?php 
                // get all data base from database
                $database = new DropitDatabase();
                $results = $database->getData();

                if ($results->user_id != null) : 
                    echo "<div class='account-info'><h1>". __( 'Dropbox Account Details', 'sendfiles')."<span class='dashicons dashicons-yes activate'></span><span class='green'>".__('Connected', 'sendfiles')."</span></h1>";
                    echo '<p><b>'.__( 'Name:-', 'sendfiles').'<span class="green">'.$results->name.'</span><br>'.__( 'Email:-','sendfiles').'<span class="green"> '.$results->email.'</span></b></p></div>';
                    ?>

                <div class="panel-content">
                    <div class="cart">
                        <?php
                        echo '<p><b>'.__( 'Step 1.', 'sendfiles').'</b>'.__( 'Please click below button to connect with another dropbox account and get the Token from pop up window.', 'sendfiles').'</p>';

                        $dropbox = new Dropbox();
                        $dropboxAuthUrl = $dropbox->getAuthUrl();

                        echo '<a class="button-primary connect-btn" target="_blank" href='.$dropboxAuthUrl.'>'.__( 'Reconnect With Dropbox', 'sendfiles').'</a>';
                        echo '<a class="button-secondary right disconnect-btn" >'.__( 'Disconnect With Dropbox', 'sendfiles').'</a><br><br>';

                else :
                    echo "<div class='panel-content'>";
                    echo '<h3>'.__( ' In order to use DropIt you will need to connect with your Dropbox account.', 'sendfiles').'</h3><br><hr>';
                    echo '<p><b>'.__( 'Step 1.</b> Please click the [Connect With Dropbox] button below and get the Token from pop up window.', 'sendfiles').'</p>';
                    $dropbox = new Dropbox();
                    $dropboxAuthUrl = $dropbox->getAuthUrl();
                    echo '<a class="button-primary connect-btn" target="_blank" href='.$dropboxAuthUrl.'>'.__( 'Connect With Dropbox', 'sendfiles').'</a><br><br>';
                endif; ?>
                    <hr>
                        <div class="token-wrapper">
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
                                        <input type="text" id="auth-token" required  placeHolder="please enter the token"/>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2">
                                        <div class="submit-wrapper">
                                            <input type="button" id="authorize-btn"   name="wp-dropit-button" class="button-primary" value="<?php echo __( 'Save Settings', 'sendfiles' ); ?>" />
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
    