var api_port=window.location.port;
api_url = "";
if(api_port >0){
    api_url = window.location.protocol +'//' + window.location.hostname+ ':'+api_port
}else{
    api_url = window.location.protocol + '//' + window.location.hostname
}
api_url += "/services.php";
var arrYear = {};
var arrGeography = {};
var arrLocation = {};
var arrEmail = {},arrDownload = {};

function JSONize(str) {
  return str
    // wrap keys without quote with valid double quote
    .replace(/([\$\w]+)\s*:/g, function(_, $1){return '"'+$1+'":'})    
    // replacing single quote wrapped ones to double quote 
    .replace(/'([^']+)'/g, function(_, $1){return '"'+$1+'"'})         
}

var filterScroll;

function scrollFilter () {
	filterScroll = new IScroll('#menu-4');
}

document.addEventListener('touchmove', function (e) { 
	if(!isMobile.any()){
		e.preventDefault(); 
	}
}, false);

var wScreen, hScreen, mWidth, animateWidth, translate3dDigits, translate3dDigitsHide, selectYear, selectGeography, selectLocation, firstFilterOpen = false;

// Begin setBG
function setBG()
{	
	wScreen = $(window).width(),
	hScreen = $(window).height(),
	mWidth = (wScreen - 1024) / 2;
	
	if(isMobile.any())
	{
		if(wScreen < 768){
			animateWidth = (wScreen - 10);
			translate3dDigits = 'translate3d(' + animateWidth + 'px, 0, 0)';
		}else if(wScreen > 767 && wScreen < 1025){
			animateWidth = 269;
			translate3dDigits = 'translate3d(' + 269 + 'px, 0, 0)';
		}else{
			animateWidth = mWidth + 275;
			translate3dDigits = 'translate3d(' + 275 + 'px, 0, 0)';
			translate3dDigitsHide = 'translate3d(-' + animateWidth + 'px, 0, 0)';
		}
	}else{
		if(wScreen < 768){			
			animateWidth = (wScreen - 10);
			translate3dDigits = 'translate3d(' + animateWidth + 'px, 0, 0)';
		}else if(wScreen > 767 && wScreen < 1025){
			animateWidth = 269;
			translate3dDigits = 'translate3d(' + 269 + 'px, 0, 0)';
		}else{
			animateWidth = mWidth + 260;
			translate3dDigits = 'translate3d(' + 275 + 'px, 0, 0)';
			translate3dDigitsHide = 'translate3d(-' + animateWidth + 'px, 0, 0)';
		}	
	}	
	
	var hSitePage=$('#site-top').height(),
		hSiteFooter = $('.site-footer').height();
	if(hSitePage < hScreen)
	{
		hSiteFooter += hScreen - hSitePage;
		$('.site-footer').css({height: hSiteFooter});
	}
	
	$('.loading-section .site-container').css({'height' : hScreen});
	$('.loading-section .site-container').removeClass("hide");
	if(wScreen > 1024)
	{
		$('.st-menu').css({'-webkit-transform': translate3dDigitsHide, 'transform': translate3dDigitsHide});
		$('.st-effect-4.st-menu-open .st-menu').css({'-webkit-transform': "translate3d(0, 0, 0)", 'transform': "translate3d(0, 0, 0)"});
	}else{
		$('.st-menu').css({'-webkit-transform': '', 'transform': ''});
		$('.st-effect-4.st-menu-open .st-menu').css({'-webkit-transform': "", 'transform': ""});
	}
	$('.st-menu').css({"width": animateWidth});	
	$('.st-effect-4.st-menu-open .st-pusher').css({'-webkit-transform': translate3dDigits, 'transform': translate3dDigits});
}	
// End setBG

// Begin dropdown menu animate
function toggleIt(navSub) {
   $(navSub).slideToggle(200, 'linear');
}
var eventMouse;

if (isMobile.any()) {
	eventMouse = 'touchstart';
}else{
	eventMouse = 'click';
} 	
// End dropdown menu animate

$(document).on('click', '.btn-link', function(e){
	if(isMobile.any()){
		e.preventDefault();
		var urlLink = $(this).attr('href');
		var urlBlank = $(this).attr('target');
		$(this).addClass('btn-hover');
		setTimeout(function(){
			$('.btn-link').removeClass('btn-hover');
			if(urlLink != null)
			{
				window.open(urlLink, urlBlank);
			}
		}, 1000);
	}
})

$(document).ready(function()
{		
	$('#btnViewDataDevice').click(function(){
		$(this).parent('.ui-btn').addClass('btn-hover');
		setTimeout(function(){
			$('.btn-view-data .ui-btn').removeClass('btn-hover'); 
		}, 1000);
	})
	
	$('.site-footer .col-left ul li a').click(function(e){
		if(isMobile.any()){
			e.preventDefault();
			var urlLink = $(this).attr('href');
			var urlBlank = $(this).attr('target');
			$(this).addClass('btn-hover');
			setTimeout(function(){
				$('.site-footer .col-left ul li a').removeClass('btn-hover');
				if(urlLink != null)
				{
					window.open(urlLink, urlBlank);
				}
			}, 1000);
		}
	})

    jQuery.validator.addMethod("isemail", function (value, element) {
        var regex = /^[\w-']+(\.[\w-']+)*@([a-zA-Z0-9]+[a-zA-Z0-9-]+(\.[a-zA-Z0-9-]+)*?\.[a-zA-Z]{2,6}|(\d{1,3}\.){3}\d{1,3})(:\d{4})?$/;
        return regex.test(value);
    }, "You must enter a valid email.");
    jQuery.validator.addMethod("lettersonly", function(value, element) 
    {
        return this.optional(element) || /^[a-z," "]+$/i.test(value);
    }, "Letters and spaces only please");
    $("#selectionDeviceForm").submit(function() {
		var error = 0;
		if($('#txtType').val()==null||$('#txtType').val()==""){
			$('#txtType-button').addClass('btn-error');
			error++;
		}else{
            $('#txtType-button').removeClass('btn-error');
        }
		if($('#txtYear').val()==""){
			$('#txtYear-button').addClass('btn-error');
			error++;
		}else{
            $('#txtYear-button').removeClass('btn-error');
        }
		if($('#txtGeography').val()==""){
			$('#txtGeography-button').addClass('btn-error');
			error++;
		}else{
            $('#txtGeography-button').removeClass('btn-error');
        }
		if($('#txtLocation').val()==""){
			$('#txtLocation-button').addClass('btn-error');
			error++;
		}else{
            $('#txtLocation-button').removeClass('btn-error');
        }
		if(error > 0){
			$('#select_error').removeClass('hide');
			return false;
		}	
		return true;
	});	
	
	// Begin performs a smooth page scroll to an anchor on the same page.
	$('.isScroll').bind('click', function(e) {
		e.preventDefault();		
		var offsetBar = $('.st-content').scrollTop(),
			offsetObj = $(this.hash).offset().top,
			offsetScroll = offsetBar + offsetObj;
		
		if(isMobile.any()){	
			$(this).addClass('btn-hover');
			setTimeout(function(){			
				$('.isScroll').removeClass('btn-hover');			
				$('html, body').animate({scrollTop:offsetScroll}, 1000);
			}, 1000);	
		}else{
			$('.st-content').animate({scrollTop:offsetScroll}, 1000);
		}	
			
	});
	// End performs a smooth page scroll to an anchor on the same page.
		
	// Begin random photos
	function getRandomPhotos(arr, count) {
		var shuffled = arr.slice(0), 
			i = arr.length, 
			min = i - count, 
			temp, 
			index;
		while (i-- > min) {
			index = Math.floor((i + 1) * Math.random());
			temp = shuffled[index];
			shuffled[index] = shuffled[i];
			shuffled[i] = temp;
		}
		return shuffled.slice(min);
	}		
	
	function randomPhotos()
	{ 
		var listAlbum = ['1','2','3','4'];
		var activeAlbum;
		var numAlbum = getRandomPhotos(listAlbum, 1);
		if(numAlbum == "1"){
			activeAlbum = ['1','2','3'];
		}else if(numAlbum == "2"){
			activeAlbum = ['4','5','6'];
		}
		else if(numAlbum == "3"){
			activeAlbum = ['7','8','9'];
		}else{
			activeAlbum = ['10','11','12'];
		}
		
		var listPhotos= $('#photos-list');
		var liPhoto="";
		for(var i = 0;i < activeAlbum.length; i++)
		{
			liPhoto += '<li class="col-xs-4 col-md-12"><span class="photo-item photo-' + activeAlbum[i] + '"></span></li>';
		}
		
		listPhotos.append(liPhoto);
	}
	
	randomPhotos();
	// End random photos
	
	// Begin setBG
	setBG();
	$(window).resize(function(){
		setBG();
	});
	// Begin setBG
	
	$(document).on("click",".cb-selection .cb-check",function() {
        var cbName = $(this).attr("name"),
			cbCheck = $(this).prop('checked');
		if(cbName == "all"){ 
			if(cbCheck){
				$(this).parents(".data-content").find(".cb-check").prop('checked', true);
			}else{
				$(this).parents(".data-content").find(".cb-check").prop('checked', false);
			}
            var source_type = $('#pck_source_type').val();
            var year = $('#pck_year').val();
            var geography_type = $('#pck_geography_type').val();

            var static_url = "http://datasurfer.sandag.org/api"+"/"+source_type+"/"+year+"/"+geography_type.replace(' ','%20');
            mlt_location = '';
            $('[name="ch_location"]').each(function(){
                if($(this).is(':checked')){
                    var str = $(this).val();
                    while(str.indexOf(' ')>-1){
                        str=str.replace(' ','%20');
                    }
                    mlt_location+="/"+str;
                }
            })
            var download_pdf = static_url+mlt_location+"/export/pdf";
            var download_xlsx = static_url+mlt_location+"/export/xlsx";
            if(isMobile.any()){
                if($(this).is(':checked')){
                    $('#cmp_email_report_device').find('option:not(:first)').remove();
                    $('#cmp_email_report_device').append($('<option>', {value: download_pdf, text: 'pdf document (pdf)'}));
                    $('#cmp_email_report_device').append($('<option>', {value: download_xlsx, text: 'microsoft excel (xls)'}));        
                    $('#cmp_email_report_device').selectmenu('enable');
                    $('#cmp_email_report_device').selectmenu('refresh');
                    
                    $('#cmp_download_report_device').find('option:not(:first)').remove();
                    $('#cmp_download_report_device').append($('<option>', {value: download_pdf, text: 'pdf document (pdf)'}));
                    $('#cmp_download_report_device').append($('<option>', {value: download_xlsx, text: 'microsoft excel (xls)'}));        
                    $('#cmp_download_report_device').selectmenu('enable');
                    $('#cmp_download_report_device').selectmenu('refresh');
                }else{
                    $('#cmp_email_report_device').selectmenu('disable');
                    $('#cmp_email_report_device').selectmenu('refresh');
                    
                    $('#cmp_download_report_device').selectmenu('disable');
                    $('#cmp_download_report_device').selectmenu('refresh');
                }
            }else{
                if($(this).is(':checked')){
                    $('#cmp_download_report').find('option:not(:first)').remove();
                    $('#cmp_download_report').append($('<option>', {value: download_pdf, text: 'pdf document (pdf)'}));
                    $('#cmp_download_report').append($('<option>', {value: download_xlsx, text: 'microsoft excel (xls)'}));        
                    $('#cmp_download_report').prop('disabled',false);
                    $('#cmp_download_report').selectpicker('refresh');
                }else{
                    $('#cmp_download_report').prop('disabled',true);
                    $('#cmp_download_report').selectpicker('refresh');
                }
                if($('[name="ch_location"]:checked').length > 1){
                    $('#cmp_email_report').find('option:not(:first)').remove();
                    $('#cmp_email_report').append($('<option>', {value: download_pdf, text: 'pdf document (pdf)'}));
                    $('#cmp_email_report').append($('<option>', {value: download_xlsx, text: 'microsoft excel (xls)'}));        
                    $('#cmp_email_report').prop('disabled',false);
                    $('#cmp_email_report').selectpicker('refresh');
                }else{
                    $('#cmp_email_report').prop('disabled',true);
                    $('#cmp_email_report').selectpicker('refresh');
                }
            }
		}
    });
	$('.cb-selection .pck_location').click(function () {            
		var cbName = $(this).attr("name"),
			cbCheck = $(this).prop('checked');
		if(cbName == "all"){ 
			if(cbCheck){
				$(this).parents(".data-content").find(".cb-check").prop('checked', true);
			}else{
				$(this).parents(".data-content").find(".cb-check").prop('checked', false);
			}
		}
	});
	
	$("#contactForm").validate({
        errorElementClass: 'error',
		rules: {
			txtName: "required",
			txtEmail: {
				required: true,
				isemail: true
			},
			txtMessage: {
				required: true,
                //lettersonly:true,
				maxlength: 400
			},
            txtName: {
                lettersonly:true,
			},
		},
		messages: {
			txtName: {
                required:"You must enter your name.",
                lettersonly:" You must enter your name."
            },
			txtEmail: "You must enter a valid email.",
			//txtSubject: "You must enter a valid email.",
			txtMessage: {
                required:"You must enter a valid message.",
                maxlength:"You must enter a valid message.",
                lettersonly:"You must enter a valid message."
            }
		},
        highlight: function (element) {
            $('#btn_send').parent('.ui-btn').addClass("btn-hover");
			setTimeout(function(){
				$('#button_change .ui-btn').removeClass("btn-hover"); 
			}, 1000);
             $(element).addClass('error');
        },
		submitHandler: function(form) {
			$('#btn_send').parent('.ui-btn').addClass("btn-hover");
			setTimeout(function(){
				$('#button_change .ui-btn').removeClass("btn-hover"); 
			}, 1000);
					
            //form.submit();
            var txtName = $('#txtName').val();
            var txtSubject = $('#txtSubject').val();
            var txtEmail = $('#txtEmail').val();
            var txtMessage = $('#txtMessage').val();
           
            $.ajax({
				url: "/contact.php",
                type: "POST",
                dataType: "json",
				data:{txtName: txtName, txtSubject: txtSubject,txtEmail:txtEmail,txtMessage:txtMessage},
				success: function (res) {
                    if(res.result){
                        $('#txtName').val('');
                        $('#txtSubject').val('');
                        $('#txtEmail').val('');
                        $('#txtMessage').val('');
                        $('#completed').removeClass("hide");
						//debugger;
                        $('#button_change').html('<a title="" href="/" target="_self" class="btn btn-green btn-link" data-ajax="false"><span class="glyphicon glyphicon-pre"></span> data surfer home</a>');
                    }
                },
                error: function (request, status, error) {
                    console.log('Error call api');
                }
            });
			
		}
	});	
	if (isMobile.any()) {
		
		$('body').addClass("detech-device");
        
	}	

});