<!-- Basic Options Tabs -->

        <?php $active_tab = isset( $_GET[ 'tab' ] ) ? $_GET[ 'tab' ] : 'display_options'; 
            $is_update = false;

            if(isset($_POST['wp-dropit-basic'])) :

                $settings = (isset($_POST['dropit'])) ? $_POST['dropit'] : array();
                $is_update = update_option( 'wp-dropit-basic', $settings );?>

                <div class="updated success notify">
                    <p><?php echo __( 'Settings Saved!', 'sendfiles' ); ?></p>
                </div>
            
            <?php endif;

            $settings = (get_option( 'wp-dropit-basic' )) ? get_option( 'wp-dropit-basic' ) : array(); 

         if ($active_tab == 'general_options' ):?>

            <div class="welcome-panel">
                    <!-- general options form, -->
                    <form method="post" >
                        <table class="form-table" style="max-width:500px;">
                            <h1><?php _e( 'Color options for file upload box', 'sendfiles' ); ?></h1>
                            <tr class="form-field">
                                <td>
                                    <label><?php echo __( 'File upload text', 'sendfiles' ); ?></label>
                                </td>
                                <td>
                                    <input type="text" value="<?php echo (isset($settings['file_text'])) ? $settings['file_text'] : 'Choose File...'; ?>" name="dropit[file_text]"/>
                                </td>
                            </tr>
                            <tr class="form-field">
                                <td>
                                    <label><?php echo __( 'File upload font color', 'sendfiles' ); ?></label>
                                </td>
                                <td>
                                    <input type="text" value="<?php echo (isset($settings['upload_font_color'])) ? $settings['upload_font_color'] : ''; ?>" name="dropit[upload_font_color]" class="color-picker" />
                                </td>
                            </tr>
                            <tr class="form-field">
                                <td>
                                    <label><?php echo __( 'Border color', 'sendfiles' ); ?></label>
                                </td>
                                <td>
                                    <input type="text" value="<?php echo (isset($settings['border_color'])) ? $settings['border_color'] : ''; ?>" name="dropit[border_color]" class="color-picker" data-alpha="true" />
                                </td>
                            </tr>
                            <tr class="form-field">
                                <td>
                                    <label><?php echo __( 'File upload loader color', 'sendfiles' ); ?></label>
                                </td>
                                <td>
                                    <input type="text" value="<?php echo (isset($settings['loading_bg_color'])) ? $settings['loading_bg_color'] : ''; ?>" name="dropit[loading_bg_color]" class="color-picker" data-alpha="true" />
                                </td>
                            </tr>
                            <tr class="form-field">
                                <td>
                                    <label><?php echo __( 'Sharable link title', 'sendfiles' ); ?></label>
                                </td>
                                <td>
                                    <input type="text" value="<?php echo (isset($settings['link_title'])) ? $settings['link_title'] : ''; ?>" name="dropit[link_title]"  />
                                </td>
                            </tr>
                            <tr class="form-field">
                                <td>
                                    <label><?php echo __( 'Sharable link title color', 'sendfiles' ); ?></label>
                                </td>
                                <td>
                                    <input type="text" value="<?php echo (isset($settings['link_title_color'])) ? $settings['link_title_color'] : ''; ?>" name="dropit[link_title_color]" class="color-picker" />
                                </td>
                            </tr>
                            <tr class="form-field">
                                <td>
                                    <label><?php echo __( 'Sharable link background color', 'sendfiles' ); ?></label>
                                </td>
                                <td>
                                    <input type="text" value="<?php echo (isset($settings['link_bg_color'])) ? $settings['link_bg_color'] : ''; ?>" name="dropit[link_bg_color]" class="color-picker" data-alpha="true" />
                                </td>
                            </tr>

                            <tr>
                                <td>
                                <h1><?php echo __( 'Uploaded files expiry', 'sendfiles' ); ?></h1>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                <?php echo __( 'Delete files after', 'sendfiles' ); ?>
                                   
                                </td>
                                <td>
                                    <input type="number" value="<?php echo (isset($settings['expiry_number'])) ? $settings['expiry_number'] : ''; ?>" name="dropit[expiry_number]" class="expiry-number" min=".1" step="any" size="3"/>
                                    <?php 
                                    if (!isset($settings['expiry_type'])) {
                                        $settings['expiry_type'] = 0;
                                    } ?>
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
                                 <small><?php _e( 'Default expiry time is 7 days (if not set)', 'sendfiles'); ?> </small>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                <input type="submit" id="set-color" name="wp-dropit-basic" class="button-primary" value="<?php echo __( 'Save Settings', 'sendfiles' ); ?>" />
                            </td>
                            </tr>
                        </table>
                    </form>
            </div>
        <?php endif; ?>


    <!-- how it works options tab -->
        <?php $active_tab = isset( $_GET[ 'tab' ] ) ? $_GET[ 'tab' ] : 'display_options'; 
         if ($active_tab == 'how_it_works' ):
         ?>
            <div class="welcome-panel">
                <h1><?php _e( 'How it works?', 'sendfiles');?></h1>      
                <h4>
                    
                    <?php _e( '1.', 'sendfiles'); ?> <span class="m-font"><a href="?page=wp-dropit&tab=dropbox_options"><?php _e( 'Connect with your Dropbox account.', 'sendfiles');?></a></span>
                </h4>
                <h4>
                    <?php _e( '2. Use','sendfiles')?> <span class="m-font"> [wpdropit] </span> <?php _e( 'shortcode anywhere in Page/Post to enable the file uploader.', 'sendfiles');?>
                </h4>
                <h4>
                   <?php _e( ' 3. It will allow you upload large files on your Dropbox account and will return sharable public URL.', 'sendfiles');?>
                </h4>
                <h4>
                   <?php _e( ' 4. To set files expiry go to', 'sendfiles'); ?> <span class="m-font"><a href="?page=wp-dropit&tab=general_options"><?php _e( 'General Options','sendfiles') ?></a></span> --> <span class="m-font"><?php _e( 'Uploaded files expiry','dropit') ?></span>
                </h4>
            </div>
        <?php endif; ?>     
