jQuery(document).ready(function($){

    // authorization for dropbox account
    $(document).on('click','#authorize-btn',function () {
        var auth_code = $('#auth-token').val();
        if (auth_code == '') {
            $('.auth-message.error').text(admin_sendfiles.add_token).fadeIn();
        }else{
            $('.auth-message').empty();
            $('#ajaxloader').show();
            $.ajax({
                url : admin_sendfiles.admin_sendfiles_url,
                type : 'post',
                data : {
                    action : 'sendfiles_authenticate',
                    auth_code : auth_code
                },
                success : function( response ) {
                    
                    if (response == 0) {
                        $('.auth-message.error').text(admin_sendfiles.token_expire).fadeIn();
                        $('#auth-token').val('');

                    }else{
                        $('.auth-message.success').text(admin_sendfiles.auth_success).fadeIn();
                        $('#auth-token').val('');
                        setTimeout(function(){ window.location.reload(true);}, 500);
                    }
                    $('#ajaxloader').hide();
                },
                error: function (textStatus, errorThrown) {
                    $('.auth-message.error').text(admin_sendfiles.error_message);
                    $('#auth-token').val('');
                    $(".loader").hide();
                }
            });
        }
    });


    // disconnect from dropbox 
    $(document).on('click','.disconnect-btn',function () {
        
        $( "#dialog-confirm" ).dialog({
            resizable: true,
            height: "auto",
            width: 400,
            modal: true,
            buttons: {
                "Yes": function() {
                    $( this ).dialog( "close" );
                    disconnectDropbox(true);
                },
                "No":function(){
                    $( this ).dialog("close");
                }
            }
        });

        function disconnectDropbox(result) {
            $('.auth-message').empty();
            $('#ajaxloader').show();
                $.ajax({
                    url : admin_sendfiles.admin_sendfiles_url,
                    type : 'post',
                    data : {
                    action : 'sendfiles_disconnect',
                    },
                    success : function( response ) {

                        if (response == 0) {
                        $('.auth-message.error').text(admin_sendfiles.error_message).fadeIn();
                        }else{
                        $('.auth-message.success').text(admin_sendfiles.disconnect_success).fadeIn();
                        setTimeout(function(){ window.location.reload(true);}, 500); 
                        }
                        $('#ajaxloader').hide();
                    },
                    error: function (textStatus, errorThrown) {
                        $('.auth-message.error').text(admin_sendfiles.error_message);
                        $('#ajaxloader').hide();
                    }
                })
        }


    });
    // slect link on focus
    $('.connect-btn').click(function(){
        $('.token-wrapper').show();
    })

});