<!-- Basic Options Tabs -->

        <?php $active_tab = isset( $_GET[ 'tab' ] ) ? $_GET[ 'tab' ] : 'display_options'; 
            $is_update = false;

            if(isset($_POST['wp-sendfiles-basic'])) :

                $settings = (isset($_POST['sendfiles'])) ? $_POST['sendfiles'] : array();
                $is_update = update_option( 'wp-sendfiles-basic', $settings );?>

                <div class="updated success notify">
                    <p><?php echo __( 'Settings Saved!', 'send-files' ); ?></p>
                </div>
            
            <?php endif;
            
            $settings = get_option( 'wp-sendfiles-basic', array());
         if ($active_tab == 'general_options' ):?>

            <div class="welcome-panel">
                    <!-- general options form -->
                    <form method="post" >
                        
                            <h1><?php _e( 'Color options for file upload box', 'send-files' ); ?></h1>
                            <div class="box-wrapper">   
                                <div class="lable">
                                    <label><?php echo __( 'File upload text', 'send-files' ); ?></label>
                                </div>
                                
                                <div class="form-field">
                                    <input type="text" value="<?php echo (isset($settings['file_text'])) ? sanitize_text_field($settings['file_text']) : 'Drop file here or click to upload.'; ?>" name="sendfiles[file_text]"/>
                                </div>
                            </div>
                            <div class="box-wrapper">   
                                <div class="lable">
                                    <label><?php echo __( 'File upload font color', 'send-files' ); ?></label>
                                </div>
                                
                                <div class="form-field">
                                    <input type="text" value="<?php echo (isset($settings['upload_font_color'])) ? sanitize_text_field($settings['upload_font_color']) : ''; ?>" name="sendfiles[upload_font_color]" class="color-picker" />
                                </div>

                                <div class="field-demo">
                                    <div style="color:<?php echo (isset($settings['upload_font_color'])) ? $settings['upload_font_color'] : ''; ?>"><?php echo (isset($settings['file_text'])) ? $settings['file_text'] : 'Drop file here or click to upload.'; ?></div>
                                </div>
                            </div>
                            <div class="box-wrapper">   
                                <div class="lable">
                                    <label><?php echo __( 'Border color', 'send-files' ); ?></label>
                                </div>
                                
                                <div class="form-field">
                                    <input type="text" value="<?php echo (isset($settings['border_color'])) ? sanitize_text_field($settings['border_color']) : ''; ?>" name="sendfiles[border_color]" class="color-picker" data-alpha="true" />
                                </div>

                                <div class="field-demo">
                                    <div class="border-demo" style="border-color:<?php echo (isset($settings['border_color'])) ? $settings['border_color'] : ''; ?>"></div>
                                </div>
                            </div>
                            <div class="box-wrapper">   
                                <div class="lable">
                                    <label><?php echo __( 'File upload loader color', 'send-files' ); ?></label>
                                </div>
                                
                                <div class="form-field">
                                    <input type="text" value="<?php echo (isset($settings['loading_border_color'])) ? sanitize_text_field($settings['loading_border_color']) : ''; ?>" name="sendfiles[loading_border_color]" class="color-picker" data-alpha="true" />
                                </div>

                                <div class="field-demo">
                                    <div class="loader" style="border-color:transparent;border-top-color:<?php echo (isset($settings['loading_border_color'])) ? $settings['loading_border_color'] : ''; ?>"></div>
                                </div>
                            </div>
                            <div class="box-wrapper">   
                                <div class="lable">
                                    <label><?php echo __( 'File upload loader backgrpund color', 'send-files' ); ?></label>
                                </div>
                                
                                <div class="form-field">
                                    <input type="text" value="<?php echo (isset($settings['loading_bg_color'])) ? sanitize_text_field($settings['loading_bg_color']) : ''; ?>" name="sendfiles[loading_bg_color]" class="color-picker" data-alpha="true" />
                                </div>

                                <div class="field-demo">
                                    <div class="loader" style="border-color:<?php echo (isset($settings['loading_bg_color'])) ? $settings['loading_bg_color'] : ''; ?>;border-top-color:<?php echo (isset($settings['loading_border_color'])) ? $settings['loading_border_color'] : ''; ?>"></div>
                                </div>
                            </div>
                            <div class="box-wrapper">   
                                <div class="lable">
                                    <label><?php echo __( 'Sharable link title', 'send-files' ); ?></label>
                                </div>
                                
                                <div class="form-field">
                                    <input type="text" value="<?php echo (isset($settings['link_title'])) ? sanitize_text_field($settings['link_title']) : 'Share this link with anyone!'; ?>" name="sendfiles[link_title]"  />
                                </div>
                            </div>

                            <div class="box-wrapper">   
                                <div class="lable">
                                    <label><?php echo __( 'Sharable link title color', 'send-files' ); ?></label>
                                </div>
                                
                                <div class="form-field">
                                    <input type="text" value="<?php echo (isset($settings['link_title_color'])) ? sanitize_text_field($settings['link_title_color']) : ''; ?>" name="sendfiles[link_title_color]" class="color-picker" />
                                </div>
                            </div>
                            <div class="box-wrapper">   
                                <div class="lable">
                                    <label><?php echo __( 'Sharable link background color', 'send-files' ); ?></label>
                                </div>
                                
                                <div class="form-field">
                                    <input type="text" value="<?php echo (isset($settings['link_bg_color'])) ? sanitize_text_field($settings['link_bg_color']) : ''; ?>" name="sendfiles[link_bg_color]" class="color-picker" data-alpha="true" />
                                </div>

                                <div class="field-demo">
                                    <div style="border-radius: 3px;background-color:<?php echo (isset($settings['link_bg_color'])) ? $settings['link_bg_color'] : ''; ?>;color:<?php echo (isset($settings['link_title_color'])) ? $settings['link_title_color'] : ''; ?>"><?php echo (isset($settings['link_title'])) ? $settings['link_title'] : 'Share this link with anyone!'; ?></div>
                                </div>
                            </div>
                            <br>
                            <h1><?php echo __( 'Uploaded files expiry', 'send-files' ); ?></h1>
                            <div class="box-wrapper">   
                                
                                <div class="lable">
                                    <?php echo __( 'Delete files after', 'send-files' ); ?>
                                    <br><small><?php _e( 'Default expiry time is 7 days (if not set)', 'send-files'); ?> </small>
                                </div>
                                
                                <div class="form-field">
                                    <input type="number" value="<?php echo (isset($settings['expiry_number'])) ? sanitize_text_field($settings['expiry_number']) : ''; ?>" name="sendfiles[expiry_number]" class="expiry-number" min=".1" step="any" size="3"/>
                                    <?php 
                                    if (!isset($settings['expiry_type'])) {
                                        $settings['expiry_type'] = 0;
                                    } ?>
                                        <select name="sendfiles[expiry_type]">
                                        <option <?php selected( $settings['expiry_type'], '0'); ?> value="0" selected><?php _e( 'Select your option', 'send-files'); ?></option>
                                        <option <?php selected( $settings['expiry_type'], 'minutes'); ?> value="minutes"><?php _e( 'Minutes', 'send-files'); ?></option> 
                                        <option <?php selected( $settings['expiry_type'], 'hours'); ?> value="hours"><?php _e( 'Hours', 'send-files'); ?></option> 
                                        <option <?php selected( $settings['expiry_type'], 'days'); ?> value="days"><?php _e( 'Days', 'send-files'); ?></option> 
                                        <option <?php selected( $settings['expiry_type'], 'months'); ?> value="months"><?php _e( 'Months', 'send-files'); ?></option>
                                    </select>
                                </div>
                            </div>
                            <div class="clear button-wrapper">
                                 <input type="submit" id="set-color" name="wp-sendfiles-basic" class="button-primary" value="<?php echo __( 'Save Settings', 'send-files' ); ?>" />
                             </div>
                            
                    </form>
            </div>
        <?php endif; ?>

    <!-- how it works options tab -->
        <?php $active_tab = isset( $_GET[ 'tab' ] ) ? $_GET[ 'tab' ] : 'display_options'; 
         if ($active_tab == 'how_it_works' ):
         ?>
            <div class="welcome-panel">
                <h1><?php _e( 'How it works?', 'send-files');?></h1>      
                <p>
                    <?php if ( is_network_admin() ) {
                            $url =  network_admin_url( 'options-general.php?page=wp-sendfiles');
                        }
                        else {
                            $url = admin_url( 'options-general.php?page=wp-sendfiles');
                        }?>
                    <?php _e( '1.', 'send-files'); ?> <a href="<?php echo $url.'&tab=dropbox_options' ?>"><?php _e( 'Connect with your Dropbox account.', 'send-files');?></a>
                </p>
                <p>
                    <?php _e( '2. Use','send-files')?>  [sendfiles] <?php _e( 'shortcode anywhere in Page/Post to enable the file uploader.', 'send-files');?>
                </p>
                <p>
                   <?php _e( ' 3. It will allow you upload large files on your Dropbox account and will return sharable public URL.', 'send-files');?>
                </p>
                <p>
                   <?php _e( ' 4. To set files expiry go to', 'send-files'); ?> <a href="<?php echo $url.'&tab=general_options' ?>"><?php _e( 'General Options','send-files') ?></a> --> <?php _e( 'Uploaded files expiry','send-files') ?>
                </p>
            </div>
        <?php endif; ?>     
