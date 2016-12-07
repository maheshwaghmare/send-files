<!-- Basic Options Tabs -->

        <?php $active_tab = isset( $_GET[ 'tab' ] ) ? $_GET[ 'tab' ] : 'display_options'; 
            $is_update = false;

            if(isset($_POST['wp-sendfiles-basic'])) :

                $settings = (isset($_POST['sendfiles'])) ? $_POST['sendfiles'] : array();
                $is_update = update_option( 'wp-sendfiles-basic', $settings );?>

                <div class="updated success notify">
                    <p><?php echo __( 'Settings Saved!', 'sendfiles' ); ?></p>
                </div>
            
            <?php endif;
            
            $settings = (get_option( 'wp-sendfiles-basic' )) ? get_option( 'wp-sendfiles-basic' ) : array(); 
         if ($active_tab == 'general_options' ):?>

            <div class="welcome-panel">
                    <!-- general options form, -->
                    <form method="post" >
                        
                            <h1><?php _e( 'Color options for file upload box', 'sendfiles' ); ?></h1>
                            <div class="box-wrapper">   
                                <div class="lable">
                                    <label><?php echo __( 'File upload text', 'sendfiles' ); ?></label>
                                </div>
                                
                                <div class="form-field">
                                    <input type="text" value="<?php echo (isset($settings['file_text'])) ? $settings['file_text'] : 'Drop file here or click to upload.'; ?>" name="sendfiles[file_text]"/>
                                </div>

                                <div class="field-demo">
                                    
                                </div>
                            </div>
                            <div class="box-wrapper">   
                                <div class="lable">
                                    <label><?php echo __( 'File upload font color', 'sendfiles' ); ?></label>
                                </div>
                                
                                <div class="form-field">
                                    <input type="text" value="<?php echo (isset($settings['upload_font_color'])) ? $settings['upload_font_color'] : ''; ?>" name="sendfiles[upload_font_color]" class="color-picker" />
                                </div>

                                <div class="field-demo">
                                    <div style="color:<?php echo (isset($settings['upload_font_color'])) ? $settings['upload_font_color'] : ''; ?>"><?php echo (isset($settings['file_text'])) ? $settings['file_text'] : 'Drop file here or click to upload.'; ?></div>
                                </div>
                            </div>
                            <div class="box-wrapper">   
                                <div class="lable">
                                    <label><?php echo __( 'Border color', 'sendfiles' ); ?></label>
                                </div>
                                
                                <div class="form-field">
                                    <input type="text" value="<?php echo (isset($settings['border_color'])) ? $settings['border_color'] : ''; ?>" name="sendfiles[border_color]" class="color-picker" data-alpha="true" />
                                </div>

                                <div class="field-demo">
                                    <div class="border-demo" style="border-color:<?php echo (isset($settings['border_color'])) ? $settings['border_color'] : ''; ?>"></div>
                                </div>
                            </div>
                            <div class="box-wrapper">   
                                <div class="lable">
                                    <label><?php echo __( 'File upload loader color', 'sendfiles' ); ?></label>
                                </div>
                                
                                <div class="form-field">
                                    <input type="text" value="<?php echo (isset($settings['loading_border_color'])) ? $settings['loading_border_color'] : ''; ?>" name="sendfiles[loading_border_color]" class="color-picker" data-alpha="true" />
                                </div>

                                <div class="field-demo">
                                    <div class="loader" style="border-color:transparent;border-top-color:<?php echo (isset($settings['loading_border_color'])) ? $settings['loading_border_color'] : ''; ?>"></div>
                                </div>
                            </div>
                            <div class="box-wrapper">   
                                <div class="lable">
                                    <label><?php echo __( 'File upload loader backgrpund color', 'sendfiles' ); ?></label>
                                </div>
                                
                                <div class="form-field">
                                    <input type="text" value="<?php echo (isset($settings['loading_bg_color'])) ? $settings['loading_bg_color'] : ''; ?>" name="sendfiles[loading_bg_color]" class="color-picker" data-alpha="true" />
                                </div>

                                <div class="field-demo">
                                    <div class="loader" style="border-color:<?php echo (isset($settings['loading_bg_color'])) ? $settings['loading_bg_color'] : ''; ?>;border-top-color:<?php echo (isset($settings['loading_border_color'])) ? $settings['loading_border_color'] : ''; ?>"></div>
                                </div>
                            </div>
                            <div class="box-wrapper">   
                                <div class="lable">
                                    <label><?php echo __( 'Sharable link title', 'sendfiles' ); ?></label>
                                </div>
                                
                                <div class="form-field">
                                    <input type="text" value="<?php echo (isset($settings['link_title'])) ? $settings['link_title'] : 'Share this link with anyone!'; ?>" name="sendfiles[link_title]"  />
                                </div>

                                <div class="field-demo">
                                    
                                </div>
                            </div>

                            <div class="box-wrapper">   
                                <div class="lable">
                                    <label><?php echo __( 'Sharable link title color', 'sendfiles' ); ?></label>
                                </div>
                                
                                <div class="form-field">
                                    <input type="text" value="<?php echo (isset($settings['link_title_color'])) ? $settings['link_title_color'] : ''; ?>" name="sendfiles[link_title_color]" class="color-picker" />
                                </div>

                                <div class="field-demo">
                                    
                                </div>
                            </div>
                            <div class="box-wrapper">   
                                <div class="lable">
                                    <label><?php echo __( 'Sharable link background color', 'sendfiles' ); ?></label>
                                </div>
                                
                                <div class="form-field">
                                    <input type="text" value="<?php echo (isset($settings['link_bg_color'])) ? $settings['link_bg_color'] : ''; ?>" name="sendfiles[link_bg_color]" class="color-picker" data-alpha="true" />
                                </div>

                                <div class="field-demo">
                                    <div style="border-radius: 3px;background-color:<?php echo (isset($settings['link_bg_color'])) ? $settings['link_bg_color'] : ''; ?>;color:<?php echo (isset($settings['link_title_color'])) ? $settings['link_title_color'] : ''; ?>"><?php echo (isset($settings['link_title'])) ? $settings['link_title'] : 'Share this link with anyone!'; ?></div>
                                </div>
                            </div>
                            <br>
                            <h1><?php echo __( 'Uploaded files expiry', 'sendfiles' ); ?></h1>
                            <div class="box-wrapper">   
                                
                                <div class="lable">
                                    <?php echo __( 'Delete files after', 'sendfiles' ); ?>
                                    <br><small><?php _e( 'Default expiry time is 7 days (if not set)', 'sendfiles'); ?> </small>
                                </div>
                                
                                <div class="form-field">
                                    <input type="number" value="<?php echo (isset($settings['expiry_number'])) ? $settings['expiry_number'] : ''; ?>" name="sendfiles[expiry_number]" class="expiry-number" min=".1" step="any" size="3"/>
                                    <?php 
                                    if (!isset($settings['expiry_type'])) {
                                        $settings['expiry_type'] = 0;
                                    } ?>
                                        <select name="sendfiles[expiry_type]">
                                        <option <?php selected( $settings['expiry_type'], '0'); ?> value="0" selected>Select your option</option>
                                        <option <?php selected( $settings['expiry_type'], 'minutes'); ?> value="minutes">Minutes</option> 
                                        <option <?php selected( $settings['expiry_type'], 'hours'); ?> value="hours">Hours</option> 
                                        <option <?php selected( $settings['expiry_type'], 'days'); ?> value="days">Days</option> 
                                        <option <?php selected( $settings['expiry_type'], 'months'); ?> value="months">Months</option>
                                    </select>
                                </div>

                                <div class="field-demo">
                                    
                                </div>
                            </div>
                            <div class="clear button-wrapper">
                                 <input type="submit" id="set-color" name="wp-sendfiles-basic" class="button-primary" value="<?php echo __( 'Save Settings', 'sendfiles' ); ?>" />
                             </div>
                            
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
                    
                    <?php _e( '1.', 'sendfiles'); ?> <span class="m-font"><a href="?page=wp-sendfiles&tab=dropbox_options"><?php _e( 'Connect with your Dropbox account.', 'sendfiles');?></a></span>
                </h4>
                <h4>
                    <?php _e( '2. Use','sendfiles')?> <span class="m-font"> [sendfiles] </span> <?php _e( 'shortcode anywhere in Page/Post to enable the file uploader.', 'sendfiles');?>
                </h4>
                <h4>
                   <?php _e( ' 3. It will allow you upload large files on your Dropbox account and will return sharable public URL.', 'sendfiles');?>
                </h4>
                <h4>
                   <?php _e( ' 4. To set files expiry go to', 'sendfiles'); ?> <span class="m-font"><a href="?page=wp-sendfiles&tab=general_options"><?php _e( 'General Options','sendfiles') ?></a></span> --> <span class="m-font"><?php _e( 'Uploaded files expiry','sendfiles') ?></span>
                </h4>
            </div>
        <?php endif; ?>     
