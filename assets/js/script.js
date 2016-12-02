(function($){
	$(document).ready(function(){

		/*
		* clipboard
		*/
		clipboard = new Clipboard('.copy-btn');

		clipboard.on('success', function(e) {
		    $(".copy-msg").fadeIn().text("link copied");
		    setTimeout(function(){ $(".copy-msg").empty().fadeOut()}, 1000);
		    e.clearSelection();
		});

		clipboard.on('error', function(e) {
		    $(".copy-msg").text("Copy to clipboard failed").fadeIn();
		    setTimeout(function(){ $(".copy-msg").empty().fadeOut()}, 1000);
		});


		/*
		* file upload box
		*/
		var $fileInput = $('.file-input');
		var $droparea = $('.file-drop-area');

		// highlight drag area
		$fileInput.on('dragenter focus click', function() {
		  $droparea.addClass('is-active');
		});

		// back to normal state
		$fileInput.on('dragleave blur drop', function() {
		  $droparea.removeClass('is-active');
		});


		// change inner text
		$fileInput.on('change', function() {
		  var filesCount = $(this)[0].files.length;
		  var $textContainer = $(this).prev('.js-set-number');

		  if (filesCount === 1) {
		    $textContainer.text($(this).val().split('\\').pop());
		  } else {
		    $textContainer.text(filesCount + ' files selected');
		  }
		  autoUploadFiles();
		});


		/*
		* file upload
		*/
		function autoUploadFiles(){
			var fd = new FormData();
			var fd = new FormData();
			var file = $(document).find('#dropit-files');	
			var individual_file = file[0].files[0];
			
			if (file[0].files.length==0) {
				$(".error-message").empty();
				$(".error-message").html("please select a file to upload");
			}
			else{
				$(".error-message").empty();
				$(".loader").show();
				fd.append("dropit-files", individual_file);
				fd.append('action', 'dropit');

				var ajaxurl = $('#dropit-form').attr('action');
				var percent = $('.percent');
				var bar = $('.bar');

				$.ajax({
					type: 'POST',
					url: ajaxurl,
					data: fd,
					contentType: false,
					processData: false,
					// xhr: function() {
					// 	myXhr = $.ajaxSettings.xhr();
					// 	if(myXhr.upload){
					// 		myXhr.upload.addEventListener('progress',progressHandlerFunction, false);
					// 	}
					// 	return myXhr;
					// },
					success: function(response){
					    $(".file-msg").text(' Drop file here or click to upload.');
					    $(".file-input").val('');
					    $(".shortlink-wrapper").show();
					    $("#shortlink").val(response);
					    $(".loader").hide();
					},
					error: function (textStatus, errorThrown) {
						 $(".error-message").empty();
						 $(".error-message").html("something went wrong please try again");
						 $(".loader").hide();
			        }
				});

			}
		}
	});


	// function progressHandlerFunction(e) {
	//     var percent=Math.round((e.loaded/e.total) * 100);
	//     $('.di-bar').css('width', percent+'%');
	// }


	/*
	* select generated shortlink
	*/
	$(document).on('click','#shortlink',function () {
		$(this).select();
		document.execCommand('copy');
		$(".copy-msg").fadeIn().text("link copied");
		setTimeout(function(){ $(".copy-msg").empty().fadeOut()}, 1000);
	});



})(jQuery);



