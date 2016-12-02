
<!-- dashboard -->
    <div class="wrap wp-dropit">
      <h1>DropIt Settings</h1>
         
        <?php
            if( isset( $_GET[ 'tab' ] ) ) {
                $active_tab = $_GET[ 'tab' ];
            }
             $active_tab = isset( $_GET[ 'tab' ] ) ? $_GET[ 'tab' ] : 'welcome';
        ?>
         
         <!-- tabs -->
         <div class="dropit-settings-nav">
            <h2 class="nav-tab-wrapper">
                <a href="?page=wp-dropit&tab=welcome" class="nav-tab <?php echo $active_tab == 'welcome' ? 'nav-tab-active' : ''; ?>">Welcome</a>
                <a href="?page=wp-dropit&tab=dropbox_options" class="nav-tab <?php echo $active_tab == 'dropbox_options' ? 'nav-tab-active' : ''; ?>">Dropbox Options</a>
                <a href="?page=wp-dropit&tab=general_options" class="nav-tab <?php echo $active_tab == 'general_options' ? 'nav-tab-active' : ''; ?>">General Options</a>
                <a href="?page=wp-dropit&tab=how_it_works" class="nav-tab <?php echo $active_tab == 'how_it_works' ? 'nav-tab-active' : ''; ?>">How It Works?</a>
            </h2>
         </div>


         <!-- welcome tabs -->
        <?php if ($active_tab == 'welcome' ):?>

            <div class="welcome-panel">
                <h1>Welcome to <span class="version">DropIt</span></h1>
                Thank you for choosing DropIt
                <h4>The easiest way to share files using Dropbox.</h4>
                <h4>It will allow you upload large files on your Dropbox account and will return sharable public URL.</h4>            <br>
                <h3>Getting Started - <a href="?page=wp-dropit&tab=dropbox_options">Connect with your Dropbox Account</a>.</h3>
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
                    echo "<div class='account-info'><h1>Dropbox Account Details <span class='dashicons dashicons-yes activate'></span><span class='green'>Connected</span></h1>";
                    echo '<p><b>Name:- <span class="green">'.$results->name.'</span><br>Email:- <span class="green"> '.$results->email.'</span></b></p></div>';
                    ?>

                <div class="panel-content">
                    <div class="cart">
                        <?php
                        echo '<p><b>Step 1</b>. Please click below button to connect with another dropbox account and get the Token from pop up window.</p>';

                        $dropbox = new Dropbox();
                        $dropboxAuthUrl = $dropbox->getAuthUrl();

                        echo '<a class="button-primary connect-btn" target="_blank" href='.$dropboxAuthUrl.'>Reconnect With Dropbox</a>';
                        echo '<a class="button-secondary right disconnect-btn" >Disconnect With Dropbox</a><br><br>';

                else :
                    echo "<div class='panel-content'>";
                    echo '<h3> In order to use DropIt you will need to connect with your Dropbox account.</h3><br><hr>';
                    echo '<p><b>Step 1.</b> Please click the [Connect With Dropbox] button below and get the Token from pop up window.</p>';
                    $dropbox = new Dropbox();
                    $dropboxAuthUrl = $dropbox->getAuthUrl();
                    echo '<a class="button-primary connect-btn" target="_blank" href='.$dropboxAuthUrl.'>Connect With Dropbox</a><br><br>';
                endif; ?>
                    <hr>
                        <div class="token-wrapper">
                            <p><b>Step 2.</b> 
                        Add Token and save settings</p>
                        <form method="post" class="token-form" name="token-form">
                            <table id="createuser" class="form-table">
                                <tr class="form-field">
                                    <td>
                                        <label><?php echo __( 'Token', 'dropit' ); ?></label>
                                    </td>
                                    <td>
                                        <input type="text" id="auth-token" required  placeHolder="please enter the token"/>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2">
                                        <div class="submit-wrapper">
                                            <input type="button" id="authorize-btn"   name="wp-dropit-button" class="button-primary" value="<?php echo __( 'Save Settings', 'dropit' ); ?>" />
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
                    <p><span class="ui-icon ui-icon-alert" style="float:left; margin:12px 12px 20px 0;"></span>Do you really want to disconnect with Dropbox?</p>
                    </div>
                </div>
            </div>

        <?php endif; ?>
    