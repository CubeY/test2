function send_email_noti_new_paying_user(uid,status)
{
	var todo = "send_email_noti_new_paying_user";
	$.ajax({  
		type: "POST", 
		url: './ajax/ajax_student.php', 
		data: "todo="+todo+"&uid="+uid+"&status="+status,
		dataType: "html",
		success: function(data){ 
		}
	});
}


function close_Upload_box()
{
	jQuery('.backdrop, .addd_phpto_from_pc , .addd_qr_img_from_pc').animate({'opacity':'0'}, 300, 'linear', function(){
		jQuery('.backdrop, .addd_phpto_from_pc , .addd_qr_img_from_pc').css('display', 'none');
	});
	jQuery('.over_backdrop').hide();
}

$(document).ready(function(e) {
  var site_url = $('#site_url').val();
	var url = site_url+'face_photo/php/';
	var qr_img_url = site_url+'qr_img/php/';
	  
jQuery('.Upload_close').click(function(){
		close_Upload_box();
	});
	
	
	$('.delPUpload').click(function(e) {
		
			jQuery('#img_data').val('');
			var thisImgSrc = site_url+'media/images/no_profile_image.png';
			jQuery('.face_photo_prevw').attr("src",thisImgSrc);
    });
	
	$('.delPUpload_qr').click(function(e) {
		
			jQuery('#qr_img_data').val('');
			var thisImgSrc = site_url+'media/images/no_bio_image-150x150.gif';
			jQuery('.qr_photo_prevw').attr("src",thisImgSrc);
    });
	
	
    
	jQuery('.addPUpload').click(function(){
			
		jQuery('.backdrop, .add_phptp_form_a').animate({'opacity':'.50'}, 300, 'linear');
		jQuery('.add_phptp_form_a').animate({'opacity':'1.00'}, 300, 'linear');
		jQuery('.backdrop, .add_phptp_form_a').css('display', 'block');
		
		var top = Math.max(0, (($(window).height() - $(this).outerHeight()) / 2) +  $(window).scrollTop());
				//$("#addd_phpto_from_pc").show();
				//$('.addd_phpto_from_pc').css('top',180+top+'px');
				
		
		jQuery('.addd_phpto_from_pc').animate({'opacity':'.50'}, 300, 'linear');
		jQuery('.addd_phpto_from_pc').animate({'opacity':'1.00'}, 300, 'linear');
		jQuery('.addd_phpto_from_pc').css('display', 'block');
		$('.addd_phpto_from_pc').css('top',top+'px');
		
		//jQuery("body").css("overflow", "hidden");
	});
	
	
	
	
	
	
	
	
	jQuery('#fileupload_from_pc').fileupload({
        url: url,
        dataType: 'json',
        done: function (e, data) {
            jQuery.each(data.result.files, function (index, file) {
              var maxImageUploadSize = 1024; 
			  jQuery('#img_data').val(file.name);
			 jQuery('#img_type').val(file.type);
			 var maxImageUploadSize = Math.round( parseInt(maxImageUploadSize) * 1024 );
			
			   // jQuery('<p/>').text(file.name).appendTo('#files');
			// 
			      var isValid = /\.(gif|jpe?g|png)$/i.test(file.name);
			if (!isValid) {
			  alert('Only gif|jpeg|png files allowed!');
			  return false;
			}
			else 
			{
					if(maxImageUploadSize < file.size)
					{alert('Max image upload size '+maxImageUploadSize+' KB.Please reduce the size'); return false;}
			}
            var progress = parseInt(data.loaded / data.total * 100, 10);

		//  jQuery('#files').html(data.loaded+'+'+data.total);
		    jQuery('#progress .bar').css(
                'width',
                progress + '%'
            );
			
			if(data.loaded == data.total)
			{
				
				setTimeout(function(){
					
					var thisImg = jQuery('#img_data').val();
					var thisImgSrc = url+'files/thumbnail/'+thisImg;
				
				
				jQuery('.addd_phpto_from_pc').css('display', 'none');
				
				jQuery('.backdrop, .after_uploaded_from_pc').animate({'opacity':'.50'}, 300, 'linear');
				jQuery('.after_uploaded_from_pc').animate({'opacity':'1.00'}, 300, 'linear');
				jQuery('.backdrop, .after_uploaded_from_pc').css('display', 'none');
				
				jQuery('.face_photo_prevw').attr("src",thisImgSrc);
 
					},1000);
			}
            });
        },
    });
	
	
	jQuery('.addPUpload_qr').click(function(){
			
		jQuery('.backdrop, .add_phptp_form_a').animate({'opacity':'.50'}, 300, 'linear');
		jQuery('.add_phptp_form_a').animate({'opacity':'1.00'}, 300, 'linear');
		jQuery('.backdrop, .add_phptp_form_a').css('display', 'block');
		var top = Math.max(0, (($(window).height() - $(this).outerHeight()) / 2) +  $(window).scrollTop());
		
		jQuery('.addd_qr_img_from_pc').animate({'opacity':'.50'}, 300, 'linear');
		jQuery('.addd_qr_img_from_pc').animate({'opacity':'1.00'}, 300, 'linear');
		jQuery('.addd_qr_img_from_pc').css('display', 'block');
		$('.addd_qr_img_from_pc').css('top',top+'px');
		
	});
	
	
	jQuery('#fileupload_from_pc_qr_img').fileupload({
        url: qr_img_url,
        dataType: 'json',
        done: function (e, data) {
            jQuery.each(data.result.files, function (index, file) {
              var maxImageUploadSize = 1024; 
			  jQuery('#qr_img_data').val(file.name);
			 var maxImageUploadSize = Math.round( parseInt(maxImageUploadSize) * 1024 );
			
			   // jQuery('<p/>').text(file.name).appendTo('#files');
			// 
			      var isValid = /\.(gif|jpe?g|png)$/i.test(file.name);
			if (!isValid) {
			  alert('Only gif|jpeg|png files allowed!');
			  return false;
			}
			else 
			{
					if(maxImageUploadSize < file.size)
					{alert('Max image upload size '+maxImageUploadSize+' KB.Please reduce the size'); return false;}
			}
            var progress = parseInt(data.loaded / data.total * 100, 10);

		//  jQuery('#files').html(data.loaded+'+'+data.total);
		    jQuery('#progress .bar').css(
                'width',
                progress + '%'
            );
			
			if(data.loaded == data.total)
			{
				
				setTimeout(function(){
					
					var thisImg = jQuery('#qr_img_data').val();
					var thisImgSrc = qr_img_url+'files/thumbnail/'+thisImg;
				
				
				jQuery('.addd_qr_img_from_pc').css('display', 'none');
				
				jQuery('.backdrop, .after_uploaded_from_pc').animate({'opacity':'.50'}, 300, 'linear');
				jQuery('.after_uploaded_from_pc').animate({'opacity':'1.00'}, 300, 'linear');
				jQuery('.backdrop, .after_uploaded_from_pc').css('display', 'none');
				
				jQuery('.qr_photo_prevw').attr("src",thisImgSrc);
 
					},1000);
			}
            });
        },
    });
	
	
	
	});	