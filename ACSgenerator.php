<?php
require_once("lib/config.php");
//require_once("lib/function.php");
require_once("generateJson.php");

$page_title = "SANDAG Data Surfer | Contact Us";
$geozonesql = "select distinct geozone from dim.mgra where series = $1 and  geotype = $2 group by geozone order by geozone;";




$isSend = false;

if (isset($_POST) && count($_POST) > 0) {
	//if ($_SERVER["REQUEST_METHOD"] == "POST") {
	//if($_POST['txtDatasource'] && $_POST['txtYear'] && $_POST['txtGeotype'] && $_POST['ddlGeoZone']){
	$datasource = $_POST['txtDatasource'];
	$year =  $_POST['ddlYear'];
	$geotype = $_POST['ddlGeoType'];
	$zone = ucwords( $_POST['ddlGeoZone']);
	$geozoneJson =  Query::getInstance()->getZonesAsJson($geozonesql,10, $geotype);
	$geozoneJsonDataObject = json_decode($geozoneJson);
	
	$zones = array();
	if($zone == 'Select All Zone'){
		foreach( $geozoneJsonDataObject as $eachZone ){
			array_push( $zones, ucwords($eachZone->geozone));
		}	
	}
	else{
		array_push($zones ,$zone);
	}
	//var_dump( $zones);
	
	foreach( $zones as $zone){
		generateACSjson($datasource, $year, $geotype, $zone);
		
	}
 
}
?><!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml"><!-- InstanceBegin template="/Templates/master.dwt" codeOutsideHTMLIsLocked="false" -->
<?php
include("head.php");
?>
<script type="text/javascript">

var api_port=window.location.port;
var api_url="";
if(api_port >0){
    api_url = window.location.protocol +'//' + window.location.hostname+ ':'+api_port
}else{
    api_url = window.location.protocol + '//' + window.location.hostname
}

  api_url += "/services.php";

$(document).ready(function(){
	$('#ddlYear').change(function(){
		var slt_year = $('#ddlYear');
		
		//var slt_geography_type = $('#slt_geography_type');
	//	var divObjGeographyType = slt_geography_type.siblings();
	//	var chlGeographyType = divObjGeographyType.children();
	//	chlGeographyType.first().removeClass('btn-error');
		
	//	var slt_location = $('#slt_location');
	//	var divObjLocation = slt_location.siblings();
	//	var chlLocation = divObjLocation.children();
	//	chlLocation.first().removeClass('btn-error');
	
		if($(this).val()!="Select a Year"){
	
			var source_type = $('#txtDatasource').val();
			var year = $('#ddlYear').val();
			var url = api_url;
			$.ajax({
				url:url,
				type:"POST",
				dataType:"json",
				data:{source_type:source_type,year:year},
                
				success: function (res) {
				    if (res.length > 0) {				     
						
						var arrTemp = [];
                        for(var i=0;i<res.length;i++){
							var obj = res[i];
							for(var key in obj){
							    arrTemp.push(obj[key]);
							 
							}
						}
                        arrTemp.sort();
						$('#ddlGeoType').empty();
						$('#ddlGeoType').append($('<option>', {value: "Select A GeoType", text: "Select A GeoType"}));
                        for(var i=0;i<arrTemp.length;i++){
							$('#ddlGeoType').append($('<option>', {value: arrTemp[i], text: arrTemp[i]}));
						}
					}
				},
				error: function (request, status, error) {
					console.log('Error call api');
				}
			});
		}
	});
	$('#ddlGeoType').change(function() {
		if($(this).val()!="Select a GeoType"){
	
			var source_type = $('#txtDatasource').val();
			var year = $('#ddlYear').val();
			var geotype = $('#ddlGeoType').val();
			
			var url = api_url;
			$.ajax({
				url:url,
				type:"POST",
				dataType:"json",
				data:{source_type:source_type,year:year,geography_type:geotype},
                
				success: function (res) {
				    if (res.length > 0) {				     
						
						var arrTempzone = [];
                        for(var i=0;i<res.length;i++){
							var obj = res[i];
							for(var key in obj){
							    arrTempzone.push(obj[key]);
							 
							}
						}
                        arrTempzone.sort();
						$('#ddlGeoZone').empty();
						$('#ddlGeoZone').append($('<option>', {value: "Select A Zone", text: "Select A Zone"}));
						$('#ddlGeoZone').append($('<option>', {value: "Select All Zone", text: "Select All Zone"}));
                        for(var i=0;i<arrTempzone.length;i++){
							
							$('#ddlGeoZone').append($('<option>', {value: arrTempzone[i], text: arrTempzone[i]}));
						}
						//$('#ddlGeoZone').selectpicker('refresh');
					}
				},
				error: function (request, status, error) {
					console.log('Error call api');
				}
				});
				}
	});
	});
	

</script>
<body>

	<!-- .st-container -->
    <div id="st-container" class="st-container">
    
        <!-- .st-menu -->
        <nav class="st-menu st-effect-4" id="menu-4">
    
			<!-- .left-nav -->
			<?php
			include("left_menu.php");
			?>        
			<!-- /.left-nav -->
			
		</nav>
		<!-- /.st-menu -->
		
		<!-- .st-pusher -->
        <div class="st-pusher">
		
			<!-- .st-content -->
        	<div class="st-content">
			
				<!-- .site-wrapper -->
				<div id="site-top" class="site-wrapper">	
					
					<!-- InstanceBeginEditable name="content" -->
					<div class="banner-section banner-contact"> 
						<div class="site-container">
							<div class="banner-img banner-img-contact">
								<div class="banner-gradient">
									<!-- .site-header -->
									<?php
									include("header_menu.php");
									?> 
									<!-- /.site-header -->
								</div>
							</div>
							<div class="banner-content clearfix"> 
								<header class="banner-header clearfix">
									<div class="site-container">
										<div class="row">
											<div class="col-xs-offset-1 col-xs-10 col-sm-9 col-md-7">
												<h4>Generate Census ACS json files </h4>
											</div>
										</div>
									</div>
								</header>                                       
							</div>
						</div>
					</div>
					
					<!-- .site-content -->
					<section class="site-content site-contact">
						<div class="site-container">
						
							<!-- .banner-section -->
                            <div class="banner-section how-section"> 	
								<div class="banner-content contact-content clearfix">	
									<!--<div class="row">
										<div class="col-xs-offset-1 col-xs-10 col-sm-9 col-left intro-section">
											<p>Feel free to contact us! Send us an email by completing the form below.</p>
										</div>
									</div> -->
									<form id="getjsonForm" method="POST" action="<?php $_PHP_SELF ?>">
										<fieldset class="contact-fieldset">
											<div class="row">
												<div class="col-xs-offset-1 col-xs-10 col-sm-2">
													<label class="contact-label">Datasource:</label>
												</div>
												<div class="col-xs-offset-1 col-xs-10 col-sm-offset-0 col-sm-5">
													<input  name="txtDatasource" id="txtDatasource" type="text"   value="censusacs"
												class="form-control" />
												</div>
											</div>
											<div class="row">
												<div class="col-xs-offset-1 col-xs-10 col-sm-2">
													
													<label class="contact-label">Year:</label>
												</div>
												<div class="col-xs-offset-1 col-xs-10 col-sm-offset-0 col-sm-5">
													<select name="ddlYear" id="ddlYear" class="form-control">
													<option value="Select a Year" selected>Select A Year</option>
													<option value="2010">2010</option>
													</select>
													<!--<input onblur="if (this.placeholder=='') this.placeholder = '2010'"  name="txtYear" id="txtYear" value="2010" type="text"   class="form-control" />
													-->
												</div>
											</div>
											<div class="row contact-row">
												<div class="col-xs-offset-1 col-xs-10 col-sm-2">
													<label class="contact-label">GeoType:</label>
												</div>
												<div class="col-xs-offset-1 col-xs-10 col-sm-offset-0 col-sm-5">
													<select name="ddlGeoType" id="ddlGeoType" class="form-control">
													<option value="Select a GeoType">Select A GeoType</option>
													<!--
													<input onblur="if (this.placeholder=='') this.placeholder = 'jurisdiction'"  name="txtGeotype" id="txtGeotype" type="text" value="jurisdiction" placeholder="Type your subject here (optional)" class="form-control" />
													-->
													</select>
												</div>
											</div>
											<div class="row">
												<div class="col-xs-offset-1 col-xs-10 col-sm-2">
													<label class="contact-label">GeoZone:</label>
												</div>
												<div class="col-xs-offset-1 col-xs-10 col-sm-offset-0 col-sm-5">
												<select name="ddlGeoZone" id="ddlGeoZone" class="form-control">
													<option value="Select All">Select A Zone</option>
													
													</select>
													<!--<input onblur="if (this.placeholder=='') this.placeholder = 'Carlsbad'" onfocus="if (this.placeholder=='Type your message here (400 character max)') this.placeholder = ''" value="Carlsbad" name="txtGeozone" id="txtGeozone" type="text" placeholder="Type your message here (400 character max)" required class="form-control" />-->
												</div>
											</div>
											<div class="row">
												<div id="button_change" class="col-xs-offset-1 col-xs-8 col-sm-offset-3 col-sm-4 col-md-3">
													<input type="submit" class="btn btn-green" id="btn_send" value="generate json" />
												
												</div>
											</div>
											<div id="completed" class="row <?php echo ($isSend)? '':'hide' ?>">
												<div class="col-xs-offset-1 col-xs-10 col-sm-offset-3 col-sm-5 intro-section">
													<h5>Thank you!</h5>
													<p>Your message has been sent! We will get back to you as soon as possible. Thank you for using Data Surfer. </p>
												</div>
											</div>
										</fieldset>
									</form>
								   
								</div>   
							</div>
					</div>
					</section>
					<!-- /.site-content -->		
					<!-- InstanceEndEditable -->
						
					
					<!-- /.site-footer -->
					<?php
					include("footer.php");
					?>
					<!-- /.site-footer -->
					
				</div> 
				<!-- /.site-wrapper -->
			
			</div>
			<!-- /.st-content -->
			
		</div>
        <!-- /.st-pusher -->
        
    </div>  
    <!-- /.st-container -->
	
    <noscript>
        <!-- must remain last thing before the form tag -->
        <p class="noJS">YOU NEED JAVASCRIPT TO RUN THIS SITE. PLEASE ENABLE JAVASCRIPT IN YOUR INTERNET OPTIONS.</p>
    </noscript>
    <!-- END HTML --> 
</body>


<!-- InstanceEnd --></html>