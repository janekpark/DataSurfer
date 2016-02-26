var api_port=window.location.port;
var api_url="";
if(api_port >0){
    api_url = window.location.protocol +'//' + window.location.hostname+ ':'+api_port
}else{
    api_url = window.location.protocol + '//' + window.location.hostname
}

api_url += "/services.php";
function isInArray(value, array) {
  return array.indexOf(value) > -1;
}

function loadYear(){
    var source_type = $('#pck_source_type').val();
    var pck_year = $('#pck_year').val();
    var pck_geography_type = $('#pck_geography_type').val();
    var pck_location = $('#pck_location').val();
    var url = api_url;
    if( $('#section_year').length >0){
        
        $.ajax({
            url:url,
            type:"POST",
            dataType:"json",
            data:{source_type:source_type},
            success: function (res) {
                if(res.length > 0){
                    var arrTemp = [];
                    for(var i=0;i<res.length;i++){
                        var obj = res[i];
                        for(var key in obj){
                            arrTemp.push(obj[key]);
                        }
                    }
                    var str_year='<div class="cb-selection"><input class="cb-check" id="cb-year-all" name="all" type="checkbox" /><label for="cb-year-all">select/unselect all</label></div>';
                    var id=2;
                    for(var i=0;i<arrTemp.length;i++){                                
                        str_year += '<div class="cb-selection">';
                        id = i+1;
                        if(pck_year==arrTemp[i]){
                            str_year += '<input checked="" class="cb-check" id="cb-year-'+id+'" type="checkbox" value="'+arrTemp[i]+'"/>';
                        }else{
                            str_year += '<input class="cb-check" id="cb-year-'+id+'" type="checkbox" value="'+arrTemp[i]+'"/>';
                        }
                        str_year += '<label for="cb-year-'+id+'">'+arrTemp[i]+'</label>';
                        str_year += '</div>';
                    }
                    if( $('#section_year').length >0){
                        $('#section_year').html(str_year);
                    }
                }
            },
            error: function (request, status, error) {
                console.log('Error call api');
            }
        });
    }
    if($('#section_location').length > 0){
        $.ajax({
            url:url,
            type:"POST",
            dataType:"json",
            data:{source_type:source_type,year:pck_year,geography_type:pck_geography_type},
            beforeSend: function(){
                $('#btnViewData').prop('disabled',false);
            },
            success: function (res) {
                if(res.length > 0){
                    var arrTemp = [];
                    for(var i=0;i<res.length;i++){
                        var obj = res[i];
                        for(var key in obj){
                            arrTemp.push(obj[key]);
                        }
                    }
                    
					var geography_type = $('#pck_geography_type').val();
					if (arrTemp.every(function checkNumbers(element, index, array) {return $.isNumeric(element);}))
					{
					    arrTemp.sort(function (a,b){return a-b;});
					} else {
					    arrTemp.sort();
					}
					
					var str_location;
						
                    var str_location='<div class="cb-selection pck_location">';
					if (arrTemp.length <= 20)
					{
					    str_location += '<input class="cb-check" id="cb-location-all" name="all" type="checkbox" />';
                        str_location +='<label for="cb-location-all">select/unselect all</label>';
					} else
					{
					    if ($( window ).width() < 568)
					      $('#h4_data_section_header').append('<br/>(Max Limit: 20)');
						else
						  $('#h4_data_section_header').append('&nbsp;&nbsp;&nbsp;(Max Limit: 20)');
					}
                    
                    str_location +='</div>';
                    var id=1;
                    for(var i=0;i<arrTemp.length;i++){
                        id=i+1;
                        str_location += '<div class="cb-selection pck_location">';
                        if(pck_location==arrTemp[i]){
                            str_location += '<input checked="" name="ch_location" class="cb-check" id="cb-location-'+id+'" type="checkbox" value="'+arrTemp[i]+'"/>';
                        }else{
                            str_location += '<input class="cb-check" name="ch_location" id="cb-location-'+id+'" type="checkbox" value="'+arrTemp[i]+'"/>';
                        }
                        str_location += '<label for="cb-location-'+id+'">'+arrTemp[i]+'</label>';
                        str_location += '</div>';
                    }
                    if($('#section_location').length > 0){
                        $('#section_location').html(str_location);
                        
                        var source_type = $('#pck_source_type').val();
                        var year = $('#pck_year').val();
                        var geography_type = $('#pck_geography_type').val();
						
						var api_url = $('#api_url').val();
                        
                        var static_url = api_url + "/"+source_type+"/"+year+"/"+geography_type.replace(' ','%20');
                        
                        $('[name="ch_location"]').on('click',function(){
                            var mlt_location = '';
							var count_check = $('[name="ch_location"]:checked').length;
                            $('[name="ch_location"]').each(function(){
                                if($(this).is(':checked')){
                                    var str_location = $(this).val();
                                    while(str_location.indexOf(' ')>-1){
                                        str_location = str_location.replace(' ','%20');
                                    }
                                    mlt_location+="/"+str_location.replace(' ','%20');
                                } else {
								    this.disabled = count_check >= 20;
								}
                            })
                            var download_pdf = static_url+mlt_location+"/export/pdf";
                            var download_xlsx = static_url+mlt_location+"/export/xlsx";
                            if(isMobile.any()){
                                if($('[name="ch_location"]:checked').length > 1){
                                    $('#cmp_email_report_device').find('option:not(:first)').remove();
                                    $('#cmp_email_report_device').append($('<option>', {value: download_pdf, text: 'pdf document (pdf)'}));
                                    $('#cmp_email_report_device').append($('<option>', {value: download_xlsx, text: 'microsoft excel (xls)'}));        
                                    $('#cmp_email_report_device').selectmenu('enable');
                                    $('#cmp_email_report_device').selectmenu('refresh');
                                }else{
                                    $('#cmp_email_report_device').selectmenu('disable');
                                    $('#cmp_email_report_device').selectmenu('refresh');
                                }
                                if($('[name="ch_location"]:checked').length > 1){
                                    $('#cmp_download_report_device').find('option:not(:first)').remove();
                                    $('#cmp_download_report_device').append($('<option>', {value: download_pdf, text: 'pdf document (pdf)'}));
                                    $('#cmp_download_report_device').append($('<option>', {value: download_xlsx, text: 'microsoft excel (xls)'}));        
                                    $('#cmp_download_report_device').selectmenu('enable');
                                    $('#cmp_download_report_device').selectmenu('refresh');
                                }else{
                                    $('#cmp_download_report_device').selectmenu('disable');
                                    $('#cmp_download_report_device').selectmenu('refresh');
                                }
                            }else{
                                if($('[name="ch_location"]:checked').length > 1){
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
                        })
                    }
                    $('#btnViewData').prop('disabled',true);
                }
            },
            error: function (request, status, error) {
                console.log('Error call api');
            }
        });
    }
}
function link_income(){
    var pck_source_type = $('#pck_source_type').val();
    var pck_year = $('#pck_year').val();
    var pck_geography_type = $('#pck_geography_type').val();
    var pck_location = $('#pck_location').val();

    var url = api_url;
    $.ajax({
        url:"/deepdiver.php",
        type:"POST",
        data:{total_population:$('#total_population').text(),chart:"5",ajax_run:"1",source_type:pck_source_type,year:pck_year,geography_type:pck_geography_type,location:pck_location},
        beforeSend: function(){
            if(isMobile.any()){
                var window_width = $(window).width();
                if(window_width < 768){
                    if(pck_source_type!=='forecast'){
                        reChart1();
                        reChart2();
                        reChart4();
                    }else{
                        reChartForecast1();
                        reChartForecast2();
                        reChartForecast4();
                    }
                }
            }
        },
        success: function (res) {
            var new_link = '/dataoverview/detailview';
            window.location = new_link;
        },
        error: function (request, status, error) {
            console.log('Error call api');
        }
    });

}
function link_age(){
    var pck_source_type = $('#pck_source_type').val();
    var pck_year = $('#pck_year').val();
    var pck_geography_type = $('#pck_geography_type').val();
    var pck_location = $('#pck_location').val();

    var url = api_url;
    $.ajax({
        url:"/deepdiver.php",
        type:"POST",
        data:{total_population:$('#total_population').text(),chart:"4",ajax_run:"1",source_type:pck_source_type,year:pck_year,geography_type:pck_geography_type,location:pck_location},
        beforeSend: function(){
            if(isMobile.any()){
                var window_width = $(window).width();
                if(window_width < 768){
                    if(pck_source_type!=='forecast'){
                        reChart1();
                        reChart2();
                        reChart5();
                    }else{
                        reChartForecast1();
                        reChartForecast2();
                        reChartForecast5();
                    }
                }
            }
        },
        success: function (res) {
            var new_link = '/dataoverview/detailview';
            window.location = new_link;
        },
        error: function (request, status, error) {
            console.log('Error call api');
        }
    });

}
function link_housing(){
    var pck_source_type = $('#pck_source_type').val();
    var pck_year = $('#pck_year').val();
    var pck_geography_type = $('#pck_geography_type').val();
    var pck_location = $('#pck_location').val();

    var url = api_url;
    $.ajax({
        url:"/deepdiver.php",
        type:"POST",
        data:{total_population:$('#total_population').text(),chart:"2",ajax_run:"1",source_type:pck_source_type,year:pck_year,geography_type:pck_geography_type,location:pck_location},
        beforeSend: function(){
            if(isMobile.any()){
                var window_width = $(window).width();
                if(window_width < 768){
                    if(pck_source_type!=='forecast'){
                        reChart1();
                        reChart4();
                        reChart5();
                    }else{
                        reChartForecast1();
                        reChartForecast4();
                        reChartForecast5();
                    }
                }
            }
        },
        success: function (res) {
            var new_link = '/dataoverview/detailview';
            window.location = new_link;
        },
        error: function (request, status, error) {
            console.log('Error call api');
        }
    });
}
function link_ethnicity(){
    var pck_source_type = $('#pck_source_type').val();
    var pck_year = $('#pck_year').val();
    var pck_geography_type = $('#pck_geography_type').val();
    var pck_location = $('#pck_location').val();

    var url = api_url;
    $.ajax({
        url:"/deepdiver.php",
        type:"POST",
        data:{total_population:$('#total_population').text(),chart:"1",ajax_run:"1",source_type:pck_source_type,year:pck_year,geography_type:pck_geography_type,location:pck_location},
        beforeSend: function(){
            if(isMobile.any()){
                var window_width = $(window).width();
                if(window_width < 768){
                    if(pck_source_type!=='forecast'){
                        reChart2();
                        reChart4();
                        reChart5();
                    }else{
                        reChartForecast2();
                        reChartForecast4();
                        reChartForecast5();
                    }
                }
            }
        },
        success: function (res) {
            var new_link = '/dataoverview/detailview';
            window.location = new_link;
        },
        error: function (request, status, error) {
            console.log('Error call api');
        }
    });

}
$(document).ready(function(){
    loadYear();
    $('#back_overview').click(function(){
        window.history.back();
    });
    $('#back_overview_device').click(function(){
        if(isMobile.Android()){
            $.mobile.back();
        }else{
            window.history.back();
        }
    });
    
	$("#selectionForm").submit(function( event ) {
		var error = 0;
		if($('#slt_source_type').val()=="" && typeof($('#slt_source_type').attr('disabled'))=='undefined'){
			var slt_source_type = $('#slt_source_type');
			var divObj = slt_source_type.siblings();
			var chl = divObj.children();
			chl.first().addClass('btn-error');
			error++;
		}
		if($('#slt_year').val()=="" && typeof($('#slt_year').attr('disabled'))=='undefined'){
			var slt_year = $('#slt_year');
			var divObj = slt_year.siblings();
			var chl = divObj.children();
			chl.first().addClass('btn-error');
			error++;
		}
		if($('#slt_geography_type').val()=="" && typeof($('#slt_geography_type').attr('disabled'))=='undefined'){
			var slt_geography_type = $('#slt_geography_type');
			var divObj = slt_geography_type.siblings();
			var chl = divObj.children();
			chl.first().addClass('btn-error');
			error++;
		}
		if($('#slt_location').val()=="" && typeof($('#slt_location').attr('disabled'))=='undefined'){
			var slt_location = $('#slt_location');
			var divObj = slt_location.siblings();
			var chl = divObj.children();
			chl.first().addClass('btn-error');
			error++;
		}
		if(error > 0){
			$('#select_error').removeClass('hide');
			return false;
		}	
		$('#btnViewData').addClass('active');
		return true;
	});
	$('#slt_source_type').change(function(){
        $('#email_report').prop('disabled',true);
        $('#email_report').selectpicker('refresh');
        $('#download_report').prop('disabled',true);
        $('#download_report').selectpicker('refresh');
        
		var slt_source_type = $('#slt_source_type');
		var divObj = slt_source_type.siblings();
		var chl = divObj.children();
		chl.first().removeClass('btn-error');
		
		var slt_year = $('#slt_year');
		var divObjYear = slt_year.siblings();
		var chlYear = divObjYear.children();
		chlYear.first().removeClass('btn-error');
		
		var slt_geography_type = $('#slt_geography_type');
		var divObjGeographyType = slt_geography_type.siblings();
		var chlGeographyType = divObjGeographyType.children();
		chlGeographyType.first().removeClass('btn-error');
		
		var slt_location = $('#slt_location');
		var divObjLocation = slt_location.siblings();
		var chlLocation = divObjLocation.children();
		chlLocation.first().removeClass('btn-error');
		if($(this).val()!=""){
			var source_type = $(this).val();
			var url = api_url;
			$.ajax({
				url:url,
				type:"POST",
				dataType:"json",
				data:{source_type:source_type},
                beforeSend: function(){
                    $('#btnViewData').prop('disabled',true);
                    $('#reset_form').prop('disabled',true);
                },
				success: function (res) {
					if(res.length > 0){
						$('#slt_year').find('option:not(:first)').remove();
						var arrTemp = [];
                        for(var i=0;i<res.length;i++){
							var obj = res[i];
							for(var key in obj){
                                arrTemp.push(obj[key]);
							}
						}
                        arrTemp.sort(function(a, b){return b-a});
                        for(var i=0;i<arrTemp.length;i++){
							$('#slt_year').append($('<option>', {value: arrTemp[i], text: source_type === 'forecast'?'Series '+arrTemp[i]:arrTemp[i]}));
                        }
					}
                    $('#slt_year').prop('disabled', false);
					$('#slt_year').selectpicker('refresh');
                    
                    $('#slt_geography_type option:first').prop('selected', true)
                    $('#slt_geography_type').prop('disabled', true);
                    $('#slt_geography_type').selectpicker('refresh');
                    
                    $('#slt_location option:first').prop('selected', true)
                    $('#slt_location').prop('disabled', true);
                    $('#slt_location').selectpicker('refresh');
					
					setTimeout(function(){ 		
						selectYear.destroy();
						selectYear = null;
						selectGeography.destroy();
						selectGeography = null;
						selectLocation.destroy();
						selectLocation = null;
						
						selectYear = new IScroll('#selectYear', {
							scrollbars: true,
							mouseWheel: true,
							interactiveScrollbars: true,
							shrinkScrollbars: 'scale',
							fadeScrollbars: true,
							click: true 
						});
						selectGeography = new IScroll('#selectGeography', {
							scrollbars: true,
							mouseWheel: true,
							interactiveScrollbars: true,
							shrinkScrollbars: 'scale',
							fadeScrollbars: true,
							click: true 
						});
						selectLocation = new IScroll('#selectLocation', {
							scrollbars: true,
							mouseWheel: true,
							interactiveScrollbars: true,
							shrinkScrollbars: 'scale',
							fadeScrollbars: true,
							click: true 
						});	
					}, 300);	
					
                    $('#btnViewData').prop('disabled',false);
                    $('#reset_form').prop('disabled',false);
				},
				error: function (request, status, error) {
					console.log('Error call api');
				}
			});
		}
	});
	
	$('#slt_year').change(function(){
        $('#email_report').prop('disabled',true);
        $('#email_report').selectpicker('refresh');
        $('#download_report').prop('disabled',true);
        $('#download_report').selectpicker('refresh');
        
		var slt_year = $('#slt_year');
		var divObjYear = slt_year.siblings();
		var chlYear = divObjYear.children();
		chlYear.first().removeClass('btn-error');
		
		var slt_geography_type = $('#slt_geography_type');
		var divObjGeographyType = slt_geography_type.siblings();
		var chlGeographyType = divObjGeographyType.children();
		chlGeographyType.first().removeClass('btn-error');
		
		var slt_location = $('#slt_location');
		var divObjLocation = slt_location.siblings();
		var chlLocation = divObjLocation.children();
		chlLocation.first().removeClass('btn-error');
		if($(this).val()!=""){
			var source_type = $('#slt_source_type').val();
			var year = $('#slt_year').val();
			var url = api_url;
			
			$.ajax({
				url:url,
				type:"POST",
				dataType:"json",
				data:{source_type:source_type,year:year},
                beforeSend: function(){
                    $('#btnViewData').prop('disabled',true);
                    $('#reset_form').prop('disabled',true);
                },
				success: function (res) {
					if(res.length > 0){
						$('#slt_geography_type').find('option:not(:first)').remove();
						var arrTemp = [];
                        for(var i=0;i<res.length;i++){
							var obj = res[i];
							for(var key in obj){
    							arrTemp.push(obj[key]);
							}
						}
                        arrTemp.sort();
                        for(var i=0;i<arrTemp.length;i++){
							$('#slt_geography_type').append($('<option>', {value: arrTemp[i], text: arrTemp[i]}));
                        }
                        $('#slt_geography_type').prop('disabled', false);
						$('#slt_geography_type').selectpicker('refresh');
                        
                        $('#slt_location option:first').prop('selected', true)
                        $('#slt_location').prop('disabled', true);
                        $('#slt_location').selectpicker('refresh');
						
						setTimeout(function(){
							selectGeography.destroy();
							selectGeography = null;
							selectLocation.destroy();
							selectLocation = null;
							
							selectGeography = new IScroll('#selectGeography', {
								scrollbars: true,
								mouseWheel: true,
								interactiveScrollbars: true,
								shrinkScrollbars: 'scale',
								fadeScrollbars: true,
								click: true 
							});
							selectLocation = new IScroll('#selectLocation', {
								scrollbars: true,
								mouseWheel: true,
								interactiveScrollbars: true,
								shrinkScrollbars: 'scale',
								fadeScrollbars: true,
								click: true 
							});	
						}, 300);	
						
                        $('#btnViewData').prop('disabled',false);
                        $('#reset_form').prop('disabled',false);
					}
				},
				error: function (request, status, error) {
					console.log('Error call api');
				}
			});
		}
	});
	$('#slt_geography_type').change(function(){
        $('#email_report').prop('disabled',true);
        $('#email_report').selectpicker('refresh');
        $('#download_report').prop('disabled',true);
        $('#download_report').selectpicker('refresh');
        
		var slt_geography_type = $('#slt_geography_type');
		var divObjGeographyType = slt_geography_type.siblings();
		var chlGeographyType = divObjGeographyType.children();
		chlGeographyType.first().removeClass('btn-error');
		
		if($(this).val()!=""){
			var source_type = $('#slt_source_type').val();
			var year = $('#slt_year').val();
			var geography_type = $('#slt_geography_type').val();
			var url = api_url;
			
			$.ajax({
				url:url,
				type:"POST",
				dataType:"json",
				data:{source_type:source_type,year:year,geography_type:geography_type},
                beforeSend: function(){
                    $('#btnViewData').prop('disabled',true);
                    $('#reset_form').prop('disabled',true);
                },
				success: function (res) {
					if(res.length > 0){
						$('#slt_location').find('option:not(:first)').remove();
						var arrTemp = [];
                        for(var i=0;i<res.length;i++){
							var obj = res[i];
							for(var key in obj){
								arrTemp.push(obj[key]);
							}
						}
						
						if (arrTemp.every(function checkNumbers(element, index, array) {return $.isNumeric(element);}))
					    {
					        arrTemp.sort(function (a,b){return a-b;});
					    } else {
					        arrTemp.sort();
					    }
						
                        for(var i=0;i<arrTemp.length;i++){
							$('#slt_location').append($('<option>', {value: arrTemp[i], text: arrTemp[i]}));
                        }
                        $('#slt_location').prop('disabled',false);
						$('#slt_location').selectpicker('refresh'); 
					}
					
					setTimeout(function(){ 
						selectLocation.destroy();
						selectLocation = null;
						
						selectLocation = new IScroll('#selectLocation', {
							scrollbars: true,
							mouseWheel: true,
							interactiveScrollbars: true,
							shrinkScrollbars: 'scale',
							fadeScrollbars: true,
							click: true 
						});	
					}, 300);	
					
                    $('#btnViewData').prop('disabled',false);
                    $('#reset_form').prop('disabled',false);
				},
				error: function (request, status, error) {
					console.log('Error call api');
				}
			});
		}
	});
	$('#slt_location').change(function(){
		var slt_location = $('#slt_location');
		var divObjLocationType = slt_location.siblings();
		var chlLocationType = divObjLocationType.children();
		chlLocationType.first().removeClass('btn-error');
	
        var source_type = $('#slt_source_type').val();
		var year = $('#slt_year').val();
		var geography_type = $('#slt_geography_type').val();
        var location = $('#slt_location').val();
        var api_url = $('#api_url').val();
        while(geography_type.indexOf(' ')>-1){
            geography_type = geography_type.replace(' ','%20');
        }
        while(location.indexOf(' ')>-1){
            location = location.replace(' ','%20');
        }
        var download_pdf = api_url+"/"+source_type+"/"+year+"/"+geography_type+"/"+location+"/export/pdf";
		var download_xlsx = api_url+"/"+source_type+"/"+year+"/"+geography_type+"/"+location+"/export/xlsx";
		
		$('#email_report').find('option:not(:first)').remove();
		$('#email_report').append($('<option>', {value: download_pdf, text: 'pdf document (pdf)'}));
		$('#email_report').append($('<option>', {value: download_xlsx, text: 'microsoft excel (xls)'}));		
        $('#email_report').prop('disabled',false);
        $('#email_report').selectpicker('refresh');
		
		$('#download_report').find('option:not(:first)').remove();
		$('#download_report').append($('<option>', {value: download_pdf, text: 'pdf document (pdf)'}));
		$('#download_report').append($('<option>', {value: download_xlsx, text: 'microsoft excel (xls)'}));
        $('#download_report').prop('disabled',false);
        $('#download_report').selectpicker('refresh');
        
		$('#overview_email_report').find('option:not(:first)').remove();
		$('#overview_email_report').append($('<option>', {value: download_pdf, text: 'pdf document (pdf)'}));
		$('#overview_email_report').append($('<option>', {value: download_xlsx, text: 'microsoft excel (xls)'}));
        $('#overview_email_report').prop('disabled',false);
        $('#overview_email_report').selectpicker('refresh');
        
		$('#overview_download_report').find('option:not(:first)').remove();
		$('#overview_download_report').append($('<option>', {value: download_pdf, text: 'pdf document (pdf)'}));
		$('#overview_download_report').append($('<option>', {value: download_xlsx, text: 'microsoft excel (xls)'}));        
        $('#overview_download_report').prop('disabled',false);
        $('#overview_download_report').selectpicker('refresh');
		
        var divObjLocation = slt_location.siblings();
		var chlLocation = divObjLocation.children();
		chlLocation.first().removeClass('btn-error');
		if($('#slt_source_type').val()!=null && $('#slt_year').val()!=null && $('#slt_geography_type').val()!=null && $('#slt_location').val()!=null){
			$('#select_error').addClass('hide');
		}
        $('#btnViewData').prop('disabled',false);
	});
    $('#txtType').change(function(){
        $('#txtEmailReport').selectmenu('disable');
		$('#txtEmailReport').selectmenu('refresh');
        $('#txtDownloadReport').selectmenu('disable');
		$('#txtDownloadReport').selectmenu('refresh');	
		if($(this).val()!=""){
            $('#txtType-button').removeClass('btn-error');
            $('#txtYear-button').removeClass('btn-error');
            $('#txtGeography-button').removeClass('btn-error');
            $('#txtLocation-button').removeClass('btn-error');
			var source_type = $(this).val();
			var url = api_url;
			$.ajax({
				url:url,
				type:"POST",
				dataType:"json",
				data:{source_type:source_type},
                beforeSend: function(){
                    $('#btnViewDataDevice').prop('disabled',true);
                    $('#reset_form').prop('disabled',true);
                },
				success: function (res) {
					if(res.length > 0){
						$('#txtYear').find('option:not(:first)').remove();
						var arrTemp = [];
                        for(var i=0;i<res.length;i++){
							var obj = res[i];
							for(var key in obj){
                                arrTemp.push(obj[key]);
							}
						}
                        arrTemp.sort(function(a, b){return b-a});
                        for(var i=0;i<arrTemp.length;i++){
							$('#txtYear').append($('<option>', {value: arrTemp[i], text: source_type === 'forecast'?'Series '+arrTemp[i]:arrTemp[i]}));
                        }
						$('#txtYear').selectmenu('enable');
						$('#txtYear').selectmenu('refresh');
                        
                        $('#txtGeography option:first').prop('selected', true)
                        $('#txtGeography').selectmenu('disable');
						$('#txtGeography').selectmenu('refresh');		
                        
                        $('#txtLocation option:first').prop('selected', true)
                        $('#txtLocation').selectmenu('disable');
						$('#txtLocation').selectmenu('refresh');
                        $('#btnViewDataDevice').prop('disabled',false);
                        $('#reset_form').prop('disabled',false);
					}
				},
				error: function (request, status, error) {
					console.log('Error call api');
				}
			});
		}
	});
	$('#txtYear').change(function(){
        $('#txtEmailReport').selectmenu('disable');
		$('#txtEmailReport').selectmenu('refresh');
        $('#txtDownloadReport').selectmenu('disable');
		$('#txtDownloadReport').selectmenu('refresh');
		var source_type = $('#txtType').val();
		var year = $('#txtYear').val();
		if($(this).val()!=""){
            $('#txtYear-button').removeClass('btn-error');
            $('#txtGeography-button').removeClass('btn-error');
            $('#txtLocation-button').removeClass('btn-error');
			var url = api_url;
			$.ajax({
				url:url,
				type:"POST",
				dataType:"json",
				data:{source_type:source_type,year:year},
                beforeSend: function(){
                    $('#btnViewDataDevice').prop('disabled',true);
                    $('#reset_form').prop('disabled',false);
                },
				success: function (res) {
					if(res.length > 0){
						$('#txtGeography').find('option:not(:first)').remove();
						var arrTemp = [];
                        for(var i=0;i<res.length;i++){
							var obj = res[i];
							for(var key in obj){
                                arrTemp.push(obj[key]);
							}
						}
                        arrTemp.sort();
                        for(var i=0;i<arrTemp.length;i++){
							$('#txtGeography').append($('<option>', {value: arrTemp[i], text: arrTemp[i].toUpperCase()}));
                        }
						$('#txtGeography').selectmenu('enable');
						$('#txtGeography').selectmenu('refresh');
                        
                        $('#txtLocation option:first').prop('selected', true)
                        $('#txtLocation').selectmenu('disable');
						$('#txtLocation').selectmenu('refresh');
                        $('#btnViewDataDevice').prop('disabled',false);
                        $('#reset_form').prop('disabled',false);
					}					
				},
				error: function (request, status, error) {
					console.log('Error call api');
				}
			});
		}
	});
	$('#txtGeography').change(function(){
        $('#txtEmailReport').selectmenu('disable');
		$('#txtEmailReport').selectmenu('refresh');
        $('#txtDownloadReport').selectmenu('disable');
		$('#txtDownloadReport').selectmenu('refresh');
		if($(this).val()!=""){
			var source_type = $('#txtType').val();
			var year = $('#txtYear').val();
			var geography_type = $('#txtGeography').val();
			var url = api_url;
            $('#txtGeography-button').removeClass('btn-error');
            $('#txtLocation-button').removeClass('btn-error');
			$.ajax({
				url:url,
				type:"POST",
				dataType:"json",
				data:{source_type:source_type,year:year,geography_type:geography_type},
                beforeSend: function(){
                    $('#btnViewDataDevice').prop('disabled',true);
                    $('#reset_form').prop('disabled',true);
                },
				success: function (res) {
					if(res.length > 0){
						$('#txtLocation').find('option:not(:first)').remove();
						var arrTemp = [];
                        for(var i=0;i<res.length;i++){
							var obj = res[i];
							for(var key in obj){
								arrTemp.push(obj[key]);
							}
						}
					
    					if (arrTemp.every(function checkNumbers(element, index, array) {return $.isNumeric(element);}))
	    				{
		    			    arrTemp.sort(function (a,b){return a-b;});
			    		} else {
				    	    arrTemp.sort();
					    }
						
                        for(var i=0;i<arrTemp.length;i++){
							$('#txtLocation').append($('<option>', {value: arrTemp[i], text: arrTemp[i].toUpperCase()}));
                        }
						$('#txtLocation').selectmenu('enable');
						$('#txtLocation').selectmenu('refresh');
                        $('#btnViewDataDevice').prop('disabled',false);
                        $('#reset_form').prop('disabled',false);
					}
				},
				error: function (request, status, error) {
					console.log('Error call api');
				}
			});
		}
	});
	$('#txtLocation').change(function(){
        $('#txtLocation-button').removeClass('btn-error');
        var source_type = $('#txtType').val();
		var year = $('#txtYear').val();
		var geography_type = $('#txtGeography').val();
        var location = $('#txtLocation').val();
		
        var api_url = $('#api_url').val();
        while(geography_type.indexOf(' ')>-1){
            geography_type = geography_type.replace(' ','%20');
        }
        while(location.indexOf(' ')>-1){
            location = location.replace(' ','%20');
        }
        var download_pdf = api_url+"/"+source_type+"/"+year+"/"+geography_type+"/"+location+"/export/pdf";
		var download_xlsx = api_url+"/"+source_type+"/"+year+"/"+geography_type+"/"+location+"/export/xlsx";

		$('#txtEmailReport').find('option:not(:first)').remove();
		$('#txtEmailReport').append($('<option>', {value: download_pdf, text: 'pdf document (pdf)'}));
		$('#txtEmailReport').append($('<option>', {value: download_xlsx, text: 'microsoft excel (xls)'}));
		$('#txtEmailReport').selectmenu('enable');
		$('#txtEmailReport').selectmenu('refresh');			
		
		$('#txtDownloadReport').find('option:not(:first)').remove();
		$('#txtDownloadReport').append($('<option>', {value: download_pdf, text: 'pdf document (pdf)'}));
		$('#txtDownloadReport').append($('<option>', {value: download_xlsx, text: 'microsoft excel (xls)'})); 
		$('#txtDownloadReport').selectmenu('enable');
		$('#txtDownloadReport').selectmenu('refresh');
		
        if($('#txtType').val()!=null && $('#txtYear').val()!=null && $('#txtGeography').val()!=null && $('#txtLocation').val()!=null){
			$('#select_error').addClass('hide');
		}
        
	});
    
    $('#cmp_email_report_device').change(function(){
        var current_link= $('#cmp_email_report_device').val();
        if(current_link!=null){
            $('#cmp_email_report_device option:first').prop('selected', true)
            sendEmailReport(current_link);
        }
    });
    $('#cmp_download_report_device').change(function(){
        var current_link= $('#cmp_download_report_device').val();
        if(current_link){
            $('#cmp_download_report_device option:first').prop('selected', true)
            window.location.href = current_link;
        }
    });
    
    $('#cmp_email_report').change(function(){
        var current_link= $('#cmp_email_report').val();
        if(current_link!=null){
            $('#cmp_email_report option:first').prop('selected', true)
            sendEmailReport(current_link);
        }
    });
    $('#cmp_download_report').change(function(){
        var current_link= $('#cmp_download_report').val();
        if(current_link){
            $('#cmp_download_report option:first').prop('selected', true)
            window.location.href = current_link;
        }
    });
    
	$('#txtEmailReport').change(function(){
        var current_link= $('#txtEmailReport').val();
        if(current_link!=null){
            $('#txtEmailReport option:first').prop('selected', true)
            sendEmailReport(current_link);
        }
    });
    $('#txtDownloadReport').change(function(){
        var current_link= $('#txtDownloadReport').val();
        if(current_link){
            $('#txtDownloadReport option:first').prop('selected', true)
            window.location.href = current_link;
        }
    });
    $('#email_report').change(function(){
        var current_link= $('#email_report').val();
        if(current_link!=null){
            $('#email_report option:first').prop('selected', true)
            sendEmailReport(current_link);
        }
    });
    $('#download_report').change(function(){
        var current_link= $('#download_report').val();
        if(current_link){
            $('#download_report option:first').prop('selected', true)
            window.location.href = current_link;
        }
    });
    $('#overview_email_report').change(function(){
        var current_link= $('#overview_email_report').val();
        if(current_link!=null){
          $('#overview_email_report option:first').prop('selected', true)
          sendEmailReport(current_link);
        }
    });
    $('#overview_download_report').change(function(){
        var current_link= $('#overview_download_report').val();
        if(current_link){
            $('#overview_download_report option:first').prop('selected', true)
            window.location.href = current_link;
        }
    });
    // email report
    $('#txtOverViewEmailReport').change(function() {
        var current_link = $('#txtOverViewEmailReport').val();
        if (current_link != null) {
            $('#txtOverViewEmailReport option:first').prop('selected', true)
            sendEmailReport(current_link);
        }
    });

    //download report
    $('#txtOverViewDownloadReport').change(function() {
        var current_link = $('#txtOverViewDownloadReport').val();
        if (current_link) {
            $('#txtOverViewDownloadReport option:first').prop('selected', true)
            window.location.href = current_link;
        }
    });
    $('#reset_form').click(function(){ 
		if (isMobile.any()) {
		
			$(this).parent('.ui-btn').addClass('btn-hover');
			setTimeout(function(){
				$('.reset-section .ui-btn').removeClass('btn-hover'); 
			}, 1000);
		
			$('#txtType').val("");
			$('#txtType').selectmenu('refresh');
            $('#txtType-button').removeClass('btn-error');		
            
			$('#txtYear').find('option:not(:first)').remove();
			$('#txtYear').selectmenu('disable');
			$('#txtYear').selectmenu('refresh');
		
			$('#txtGeography').find('option:not(:first)').remove();
			$('#txtGeography').selectmenu('disable');
			$('#txtGeography').selectmenu('refresh');
		
			$('#txtLocation').find('option:not(:first)').remove();
			$('#txtLocation').selectmenu('disable');
			$('#txtLocation').selectmenu('refresh');
			
			$('#txtEmailReport').find('option:not(:first)').remove();
			$('#txtEmailReport').selectmenu('disable');
			$('#txtEmailReport').selectmenu('refresh');	
		
			$('#txtDownloadReport').find('option:not(:first)').remove();
			$('#txtDownloadReport').selectmenu('disable');
			$('#txtDownloadReport').selectmenu('refresh');		
		}else{
			$('#slt_source_type').val("");
			$('#slt_source_type').selectpicker('refresh');
            var slt_source_type = $('#slt_source_type');
            var divObjType = slt_source_type.siblings();
            var chlType = divObjType.children();
            chlType.first().removeClass('btn-error');
			
			$('#slt_year').find('option:not(:first)').remove();
			$('#slt_year').prop('disabled',true);
			$('#slt_year').selectpicker('refresh');
			
			$('#slt_geography_type').find('option:not(:first)').remove();
			$('#slt_geography_type').prop('disabled',true);
			$('#slt_geography_type').selectpicker('refresh');
			
			$('#slt_location').find('option:not(:first)').remove();
			$('#slt_location').prop('disabled',true);
			$('#slt_location').selectpicker('refresh');
			
			$('#email_report').find('option:not(:first)').remove();
			$('#email_report').prop('disabled',true);
			$('#email_report').selectpicker('refresh');
			
			$('#download_report').find('option:not(:first)').remove();
			$('#download_report').prop('disabled',true);
			$('#download_report').selectpicker('refresh');
            $('#select_error').addClass('hide');
		}
    });
});

function sendEmailReport(current_link) {
    if (current_link != null) {
        var d = new Date();
        var subject = "SANDAG Data Surfer Report " + d.toString();
        var body = "";
        body = "Your%20report%20has%20been%20generated%20from%20SANDAG%20Data%20Surfer%20and%20is%20ready%20to%20be%20downloaded.%20Please%20click%20the%20link%20below%20to%20download."
        body += "%0A%0A";
        body += encodeURIComponent(current_link);
        body += "%0A%0A";
        body += "Thank%20you%20for%20using%20Data%20Surfer%20for%20the%20most%20accurate%20information%20on%20the%20San%20Diego%20region."
        body += "%0A%0A";
        body += "Sincerely,";
        body += "%0A";
        body += "SANDAG ";
        var open_mail = "mailto:?subject=" + encodeURIComponent(subject) + "&body=" + body;
        window.location.href = open_mail;
    }
}
