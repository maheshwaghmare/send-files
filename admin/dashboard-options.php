<!-- Basic Options Tabs -->
        <?php $active_tab = isset( $_GET[ 'tab' ] ) ? $_GET[ 'tab' ] : 'display_options'; 
            $is_update = false;
            if(isset($_POST['wp-dropit-basic'])) {
                $settings = (isset($_POST['dropit'])) ? $_POST['dropit'] : array();
                $is_update = update_option( 'wp-dropit-basic', $settings );?>

                <div class="updated success notify">
                    <p><?php echo __( 'Settings Saved!', 'dropit' ); ?></p>
                </div>
    <?php }

            $settings = (get_option( 'wp-dropit-basic' )) ? get_option( 'wp-dropit-basic' ) : array(); 

         if ($active_tab == 'general_options' ):
         ?>
            <div class="welcome-panel">
                    <form method="post" >
                        <table class="form-table" style="max-width:500px;">
                            <h1>Color options for file upload box</h1>
                            <tr class="form-field">
                                <td>
                                    <label><?php echo __( 'File upload text', 'dropit' ); ?></label>
                                </td>
                                <td>
                                    <input type="text" value="<?php echo (isset($settings['file_text'])) ? $settings['file_text'] : 'Choose File...'; ?>" name="dropit[file_text]"/>
                                </td>
                            </tr>
                            <tr class="form-field">
                                <td>
                                    <label><?php echo __( 'File upload icon color', 'dropit' ); ?></label>
                                </td>
                                <td>
                                    <input type="text" value="<?php echo (isset($settings['file_icon_color'])) ? $settings['file_icon_color'] : ''; ?>" name="dropit[file_icon_color]" class="my-color-field" />
                                </td>
                            </tr>
                            <tr class="form-field">
                                <td>
                                    <label><?php echo __( 'File upload font color', 'dropit' ); ?></label>
                                </td>
                                <td>
                                    <input type="text" value="<?php echo (isset($settings['upload_font_color'])) ? $settings['upload_font_color'] : ''; ?>" name="dropit[upload_font_color]" class="my-color-field" />
                                </td>
                            </tr>
                            <tr class="form-field">
                                <td>
                                    <label><?php echo __( 'Border color', 'dropit' ); ?></label>
                                </td>
                                <td>
                                    <input type="text" value="<?php echo (isset($settings['border_color'])) ? $settings['border_color'] : ''; ?>" name="dropit[border_color]" class="my-color-field" />
                                </td>
                            </tr>
                            <tr class="form-field">
                                <td>
                                    <label><?php echo __( 'File upload loader color', 'dropit' ); ?></label>
                                </td>
                                <td>
                                    <input type="text" value="<?php echo (isset($settings['loading_bg_color'])) ? $settings['loading_bg_color'] : ''; ?>" name="dropit[loading_bg_color]" class="my-color-field" />
                                </td>
                            </tr>
                            <tr class="form-field">
                                <td>
                                    <label><?php echo __( 'Sharable link title color', 'dropit' ); ?></label>
                                </td>
                                <td>
                                    <input type="text" value="<?php echo (isset($settings['link_title_color'])) ? $settings['link_title_color'] : ''; ?>" name="dropit[link_title_color]" class="my-color-field" />
                                </td>
                            </tr>
                            <tr class="form-field">
                                <td>
                                    <label><?php echo __( 'Sharable link background color', 'dropit' ); ?></label>
                                </td>
                                <td>
                                    <input type="text" value="<?php echo (isset($settings['link_bg_color'])) ? $settings['link_bg_color'] : ''; ?>" name="dropit[link_bg_color]" class="my-color-field" />
                                </td>
                            </tr>

                            <tr>
                                <td>
                                <h1>Uploaded files expiry</h1>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                Delete files after
                                   
                                </td>
                                <td>
                                    <!-- need to handle notice for expiry_type variable -->
                                    <input type="number" value="<?php echo (isset($settings['expiry_number'])) ? $settings['expiry_number'] : ''; ?>" name="dropit[expiry_number]" class="expiry-number" min=".1" step="any" size="3"/>
                                       <select name="dropit[expiry_type]">
                                        <option <?php selected( $settings['expiry_type'], '0'); ?> value="0" selected>Select your option</option>
                                        <option <?php selected( $settings['expiry_type'], 'minutes'); ?> value="minutes">Minutes</option> 
                                        <option <?php selected( $settings['expiry_type'], 'hours'); ?> value="hours">Hours</option> 
                                        <option <?php selected( $settings['expiry_type'], 'days'); ?> value="days">Days</option> 
                                        <option <?php selected( $settings['expiry_type'], 'months'); ?> value="months">Months</option>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                 <small>Default expiry time is 7 days </small>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                <input type="submit" id="set-color" name="wp-dropit-basic" class="button-primary" value="<?php echo __( 'Save Settings', 'dropit' ); ?>" />
                            </td>
                            </tr>
                        </table>
                    </form>
            </div>


<?php endif; ?>


    <!-- Basic Options Tabs -->
        <?php $active_tab = isset( $_GET[ 'tab' ] ) ? $_GET[ 'tab' ] : 'display_options'; 
         if ($active_tab == 'how_it_works' ):
         ?>
            <div class="welcome-panel">
                <h1>How it works?</h1>      
                <h4>
                    
                    1. <span class="m-font"><a href="?page=wp-dropit&tab=dropbox_options">Connect with your Dropbox account</a></span>.
                </h4>
                <h4>
                    2. Use <span class="m-font"> [wpdropit] </span> shortcode anywhere in Page/Post to enable the file uploader.
                </h4>
                <h4>
                    3. It will allow you upload large files on your Dropbox account and will return sharable public URL.
                </h4>
                <h4>
                    4. To set files expiry go to <span class="m-font"><a href="?page=wp-dropit&tab=general_options">General Options</a></span> --> <span class="m-font">Uploaded files expiry</span>
                </h4>
            </div>
        <?php endif; ?>     
