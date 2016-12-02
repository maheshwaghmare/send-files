jQuery(document).ready(function($){
   var myOptions = {
    defaultColor: false,
    change: function(event, ui){},
    clear: function() {},
    hide: true,
    palettes: true
};


    $(document).on('click','#authorize-btn',function () {
        var auth_code = $('#auth-token').val();
        if (auth_code == '') {
            $('.auth-message.error').text("Please add the Token").fadeIn();
        }else{
            $('.auth-message').empty();
            $('#ajaxloader').show();
            $.ajax({
                url : admin_dropit.admin_dropit_url,
                type : 'post',
                data : {
                    action : 'dropit_authenticate',
                    auth_code : auth_code
                },
                success : function( response ) {
                    
                    if (response == 0) {
                        // window.location.reload(true);
                        $('.auth-message.error').text("Token doesn't exist or has expired, please try to add valid token.").fadeIn();
                        // console.log("There was an error authorizing the plugin with your Dropbox account. Please try again.")
                        $('#auth-token').val('');

                    }else{
                        $('.auth-message.success').text("Authentication is completed. Please Wait").fadeIn();
                        $('#auth-token').val('');
                        setTimeout(function(){ window.location.reload(true);}, 500);
                         
                    }
                    $('#ajaxloader').hide();
                },
                error: function (textStatus, errorThrown) {
                    $('.auth-message.error').text("something went wrong please try again");
                    $('#auth-token').val('');
                    $(".loader").hide();
                }
            });
        }
    });

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
            $('#ajaxloader').show();
                $.ajax({
                    url : admin_dropit.admin_dropit_url,
                    type : 'post',
                    data : {
                    action : 'dropit_disconnect',
                    },
                    success : function( response ) {

                        if (response == 0) {
                        $('.auth-message.error').text("Token doesn't exist or has expired, please try to reconnect with Dropbox.").fadeIn();
                        }else{
                        $('.auth-message.success').text("Successfully Disconnected. Please Wait").fadeIn();
                        setTimeout(function(){ window.location.reload(true);}, 500); 
                        }
                        $('#ajaxloader').hide();
                    },
                    error: function (textStatus, errorThrown) {
                        $('.auth-message.error').text("something went wrong please try again");
                        $('#ajaxloader').hide();
                    }
                })
        }


    });
    // slect link on focus
    $('.connect-btn').click(function(){
        $('.token-wrapper').show();
    })

    // wpColorPicker
    $('.my-color-field').wpColorPicker(myOptions);
});