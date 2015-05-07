<?php
header('Cache-Control: no cache'); //no cache
session_cache_limiter('must-revalidate');
session_start();
require_once("lib/config.php");
require_once("lib/function.php");
require_once 'lib/Mobile_Detect.php';
$detect = new Mobile_Detect;
$deviceType = ($detect->isMobile() ? ($detect->isTablet() ? 'tablet' : 'phone') : 'computer');
if (isset($_POST) && count($_POST) > 0) {
    $device = isset($_POST['evr'])?$_POST['evr']:null;
    if($device){
        $source_type = isset($_POST['txtType'])?$_POST['txtType']:null;
        $year = isset($_POST['txtYear'])?$_POST['txtYear']:null;
        $geography_type = isset($_POST['txtGeography'])?$_POST['txtGeography']:null;
        $location = isset($_POST['txtLocation'])?$_POST['txtLocation']:null;
    }else{
        $source_type = isset($_POST['slt_source_type'])?$_POST['slt_source_type']:null;
        $year = isset($_POST['slt_year'])?$_POST['slt_year']:null;
        $geography_type = isset($_POST['slt_geography_type'])?$_POST['slt_geography_type']:null;
        $location = isset($_POST['slt_location'])?$_POST['slt_location']:null;
    }
    $_SESSION['source_type']   = $source_type;
    $_SESSION['year']   = $year;
    $_SESSION['geography_type']   = $geography_type;
    $_SESSION['location']   = $location;
} else {
    $source_type = isset($_SESSION['source_type'])?$_SESSION['source_type']:null;
    $year = isset($_SESSION['year'])?$_SESSION['year']:null; 
    $geography_type = isset($_SESSION['geography_type'])?$_SESSION['geography_type']:null;
    $location = isset($_SESSION['location'])?$_SESSION['location']:null;
}
$url = API_URL;
$url_san_income = "";
if (isset($source_type)) {
    $url.= "/" . $source_type;
}
if (isset($year)) {
    $url .= "/" . $year;
    $url_san_income .= $url."/region/san%20diego/income";
}

if (isset($geography_type)) {
    $url.= "/" . rawurlencode($geography_type);
}
if (isset($location)) {
    $url.= "/" . rawurlencode($location);
}
$detail_ethnicity="overview-details-1.php?source_type=".  urlencode($source_type)."&year=".$year."&geography_type=".$geography_type."&location=".$location;
$url_ethnicity = $url . "/ethnicity";
$url_age = $url . "/age";
$url_housing = $url . "/housing";
$url_income = $url . "/income";
$url_jobs = $url . "/jobs";
$url_map = $url . "/map";
$urlFolder = DIR_DOWNLOAD.'sandag_'.$source_type.'_'.$year.'_'.str_replace(' ', '-', $geography_type).'_'.str_replace(' ', '-', $location).'.pdf';
downloadFileFromApi($urlFolder, $url.'/export/pdf');

$urlDownloadPdf = $url.'/export/pdf';
$urlDownloadElx = $url.'/export/xlsx';
// $urlDownloadCsv = $url.'/export/csvx';
$page_title="SANDAG Data Surfer | Data Overview";
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml"><!-- InstanceBegin template="/Templates/master.dwt" codeOutsideHTMLIsLocked="false" -->
<?php include("head.php"); ?>
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
		<!-- /.cbp-spmenu -->
		
		<!-- .st-pusher -->
        <div class="st-pusher">
        
        	<!-- .st-content -->
        	<div class="st-content"> 
			
				<!-- .site-wrapper -->
				<div id="site-top" class="site-wrapper">
				
					<!-- .site-header -->
					<?php
					include("header_menu.php");
					?> 
					<!-- /.site-header -->
					
					
					<!-- InstanceBeginEditable name="content" -->
					<!-- .overview-section -->
					<section id="loading-section" class="loading-section">   
                        <div class="site-container hide">  
							<div>
								<span>
									<img class="img-no-retina" alt="" src="/content/images/ui/ajax-loader.gif" />
									<img class="img-retina" alt="" src="/content/images/ui/ajax-loader@2x.gif" />
								</span>
								gathering data...
							</div>
                        </div>
                    </section>
					<div id="overview-body" class="visibility">
						<section id="overview-section" class="overview-section clearfix">
							<div class="site-container">
								<header class="clearfix">
									<div class="row">
										<div class="col-xs-12 col-sm-7 col-md-8">
											<!-- .breadcrumb -->
											<ol class="breadcrumb">
												<li><a href="javascript:void(0);" data-ajax="false"><?php echo $source_type;?></a></li>
												<li><a href="javascript:void(0);" data-ajax="false"><?php echo $year;?></a></li>
												<li><a href="javascript:void(0);" data-ajax="false"><?php echo $geography_type;?></a></li>
												<li class="active"><?php echo $location;?></li>
											</ol>
											<!-- /.breadcrumb -->
										</div>
										<div class="col-xs-12 col-sm-5 col-md-4">
											<dl>
												<dt><span id="txt_total_population"><?php if($source_type!="forecast"){?>total population <?php }?></span></dt>
												<dd><span id="total_population"></span></dd>
											</dl>
										</div>
									</div>
									
									<hr />
									<div id="title_over_view" class="overview-intro">
										<p>Select a section of a chart or the plus button to interact with the data.</p>
									</div>
									<div id="title_over_view_detail" class="overview-intro hide">
										<a title="" class="btn btn-green hidden-xs" href="javascript:void(0);" id="back_overview" data-ajax="false"><span class="glyphicon glyphicon-pre"></span> back</a>
                                        <span id="header_label"><p>Select a section of the chart to interact with the data.<br /> Use the chart icon to download the chart image only.</p></span>
										<a title="" class="btn btn-green visible-xs"  href="javascript:void(0);" id="back_overview_device" data-ajax="false"><span class="glyphicon glyphicon-pre"></span> back</a>
									</div>
								</header>
								
								<!-- .chart-list-->
								<div id="over_view" class="chart-list">
								
									<div class="row">
										<div class="col-xs-12 col-sm-6 col-md-4">
											<div class="chart-item">
												<h4>race &amp; ethnicity</h4>
												<div class="chart-map">
													<div id="chart-1" class="chart-frame"></div>
													<a class="chart-icon isScroll" id="link_ethnicity" title="" href="#site-top" data-ajax="false"></a>
												</div>
											</div>
										</div>
										<div class="col-xs-12 col-sm-6 col-md-4">
											<div class="chart-item">
												<h4>housing types</h4>
												<div class="chart-map">
													<div id="chart-2" class="chart-frame"></div>
													<a class="chart-icon isScroll" title="" href="#site-top" id="link_housing" data-ajax="false"></a>
												</div>
												
											</div>
										</div>
										<div class="col-xs-12 col-sm-6 col-md-4">
											<div class="chart-item">
												<h4>area map</h4>
												<div class="chart-map">
													<div id="chart-3" class="chart-frame chart-img" style="background-image:url(content/images/map.png);"></div>
												</div>
											</div>
										</div>
										<?php if($source_type=='forecast') { ?>
										<div class="col-xs-12 col-sm-6">
											<div class="chart-item">
												<h4>household income</h4>
												<div class="chart-map">                                        	
													<div id="chart-5" class="chart-frame"></div>
													<a class="chart-icon isScroll" title="" href="#site-top" id="link_income" data-ajax="false"></a>
												</div>
											</div>
										</div> 
										<div class="col-xs-12 col-sm-6">
											<div class="chart-item">
												<h4>Civilian Jobs</h4>
												<div class="chart-map">
													<div id="chart-4" class="chart-frame"></div>
													<a class="chart-icon isScroll" title="" href="#site-top" id="link_age" data-ajax="false"></a>
												</div>
											</div>
										</div>
										
										<?php } else { ?>
										<div class="col-xs-12 col-sm-6">
											<div class="chart-item">
												<h4>age by gender</h4>
												<div class="chart-map">
													<div id="chart-4" class="chart-frame"></div>
													<a class="chart-icon isScroll" title="" href="#site-top" id="link_age" data-ajax="false"></a>
												</div>
											</div>
										</div>
										<div class="col-xs-12 col-sm-6">
											<div class="chart-item">
												<h4>household income</h4>
												<div class="chart-map">                                        	
													<div id="chart-5" class="chart-frame"></div>
													<a class="chart-icon isScroll" title="" href="#site-top" id="link_income" data-ajax="false"></a>
												</div>
											</div>
										</div> 
										<?php }  ?>
									</div>
									
								</div>
								<!-- /.chart-list -->
								
								<!-- .chart-detail-->
								<div id="over_view_details" class="chart-details hide">

									<div class="row">
										<div class="col-xs-12">
											<div class="chart-item">
												<h4><span id="chart_label"></span></h4>
												<div class="chart-map detail-chart">
													<div id="chart-detail" style="width:100%;margin: 0 auto" class="chart-frame"></div>
													<div id="chart-detail_2" style="width:100%;margin: 0 auto" class="chart-frame"></div>
													<div id="chart-detail_3" style="width:100%;margin: 0 auto" class="chart-frame"></div>
													<div id="chart-detail_4" style="width:100%;margin: 0 auto" class="chart-frame"></div>
												</div>
											</div>
										</div>                            
									</div>

								</div>
								<!-- /.chart-detail -->
							
							</div>
						</section>
						<!-- /.overview-section -->
						
						<!-- .report-section -->
						<section class="view-report-section">
							<div class="site-container">
								<div class="row">
									<div class="col-xs-offset-0 col-xs-12 col-sm-offset-3 col-sm-6 col-md-offset-4 col-md-4">
										<div class="btn-section">
											<input type="button" class="btn btn-brown" id ="view_full_report" value="view full report (pdf)" />
										</div>
									</div>
								</div>
							</div>
						</section>
						<!-- /.report-section -->
						
						<!-- .data-section -->
						<section id="data_section" class="data-section clearfix">
							<div class="site-container">
								<div class="row">
									<div class="col-xs-offset-1 col-xs-10 col-sm-offset-2 col-sm-8 col-md-offset-3 col-md-6">
										<header>
											<h2>YOUR DATA, QUICKLY AND EASILY</h2>
										</header>
									</div>
								</div>
								<div class="row">
									<!-- .col-left -->
									<div class="col-xs-12 col-sm-6 col-left">
									
										<!-- .data-info -->
										<div class="data-info">
											<div class="row">
												<header class="col-xs-offset-1 col-xs-10 col-sm-offset-2 col-sm-8 col-md-offset-4 col-md-6">
													<h4>Current Data</h4>
													<p>Export a full report of your current data selection. Choose your export option below.</p>
												</header>
											</div>
											<div class="row">
												<div class="col-xs-offset-2 col-xs-8 col-sm-offset-2 col-sm-8 col-md-offset-4 col-md-6">
													<div class="on-desktop">
														<div class="select-site">
															<select class="selectpicker" id="overview_email_report">
																<option value="" style="display: none;">EMAIL REPORT AS</option>                    
																<option value="<?php echo $urlDownloadPdf; ?>">pdf document (pdf)</option>
																<option value="<?php echo $urlDownloadElx; ?>">microsoft excel (xls)</option>
															</select>
														</div>
														<div class="select-site">
															<select class="selectpicker" id = "overview_download_report">
																<option value="" style="display: none;">DOWNLOAD REPORT AS</option>
																<option value="<?php echo $urlDownloadPdf; ?>">pdf document (pdf)</option>
																<option value="<?php echo $urlDownloadElx; ?>">microsoft excel (xls)</option>
															</select>
														</div>
													</div>
													<div class="on-device">
														<div class="select-site">
															<select class="select-input" id="txtOverViewEmailReport">
																<option value="">EMAIL REPORT AS</option>                  
																<option value="<?php echo $urlDownloadPdf; ?>">pdf document (pdf)</option>
																<option value="<?php echo $urlDownloadElx; ?>">microsoft excel (xls)</option>
															</select>
														</div>
														<div class="select-site">
															<select class="select-input" id="txtOverViewDownloadReport">
																<option value="">DOWNLOAD REPORT AS</option>
																<option value="<?php echo $urlDownloadPdf; ?>">pdf document (pdf)</option>
																<option value="<?php echo $urlDownloadElx; ?>">microsoft excel (xls)</option>
															</select>
														</div>
													</div>
												</div>
											</div>
										</div>
										<!-- /.data-info -->
										
									</div>
									<!-- /.col-left -->
									
									<!-- .col-right -->
									<div class="col-xs-12 col-sm-6 col-right">									
										<div class="data-line hidden-xs"></div>
											
										<!-- .data-info -->
										<div class="data-info">
											<div class="row">
												<header class="col-xs-offset-1 col-xs-10 col-sm-offset-0 col-sm-10 col-md-offset-2 col-md-8">
													<hr class="visible-xs" />
													<h4>Add and Compare Data</h4>
													<p>Your current data search is<br class="visible-xs" /> pre-selected below. To add additional<br class="visible-xs" /> locations to your report, check the<br class="visible-xs" /> applicable boxes below.</p>
												</header>
											</div>
											<fieldset class="data-fieldset">
												<div class="row">
													<div class="col-xs-12 col-sm-10 col-md-offset-2 col-md-8">
													
														<!-- .year-selection -->
														<!--<div class="data-selection">
															<h4>SELECT ADDITIONAL YEARS</h4>
															<div class="data-scroll">
																<div class="data-content" id="section_year">
																</div>
															</div>
														</div>-->
														<!-- /.year-selection -->
													
														<!-- .location-selection -->
														<div class="data-selection">
															<h4>SELECT ADDITIONAL LOCATIONS</h4>
															<div class="data-scroll">
																<div class="data-content" id="section_location">
																	  
																</div>                                     
															</div>
														</div>
														<!-- /.location-selection -->
												
													</div>
												</div>
												<div class="row">
													<div class="col-xs-offset-2 col-xs-8 col-sm-offset-0 col-sm-8 col-md-offset-2 col-md-6">
														<div class="on-desktop">
															<div class="select-site">
																<select class="selectpicker" id="cmp_email_report">
																	<option value="" style="display: none;" disabled>EMAIL REPORTS AS</option>
																</select>
															</div>
															<div class="select-site">
																<select class="selectpicker" id="cmp_download_report">
																	<option value="" style="display: none;" disabled>DOWNLOAD REPORTS AS</option>
																</select>
															</div>
														</div>
														<div class="on-device">
															<div class="select-site">
																<select disabled="disabled" class="select-input" id="cmp_email_report_device">
																	<option value="">EMAIL REPORTS AS</option>
																</select>
															</div>
															<div class="select-site">
																<select disabled="disabled" class="select-input" id="cmp_download_report_device">
																	<option value="">DOWNLOAD REPORTS AS</option>
																</select>
															</div>
														</div>
													</div>
												</div>
											</fieldset>
										</div>
										<!-- /.data-info -->
										
									</div>
									<!-- /.col-right -->
								</div>
							</div>
						</section>
						<!-- /.data-section -->
					
					</div>
					<!-- InstanceEndEditable -->
						
					<!-- /.site-content -->
					
					<!-- /.site-footer -->
					 <?php include("footer.php"); ?> 
					<!-- /.site-footer -->
					
				</div> 
				<!-- /.site-wrapper -->
			
			</div>
			<!-- /.st-content -->
			
		</div>
		<!-- /.st-pusher -->
		
	</div>
	<!-- .st-container -->
	
    <input type="hidden" id="url_ethnicity" value="<?php echo $url_ethnicity;?>"/>
    <input type="hidden" id="url_age" value="<?php echo $url_age;?>"/>
    <input type="hidden" id="url_housing" value="<?php echo $url_housing;?>"/>
    <input type="hidden" id="url_income" value="<?php echo $url_income;?>"/>
    <input type="hidden" id="url_jobs" value="<?php echo $url_jobs;?>"/>
    <input type="hidden" id="url_san_income" value="<?php echo $url_san_income;?>"/>
    <input type="hidden" id="url_map" value="<?php echo $url_map;?>"/>
    
    <input type="hidden" id="pck_source_type" value="<?php echo $source_type;?>"/>
    <input type="hidden" id="pck_geography_type" value="<?php echo $geography_type;?>"/>
    <input type="hidden" id="pck_year" value="<?php echo $year;?>"/>
    <input type="hidden" id="pck_location" value="<?php echo $location;?>"/>
    <input type="hidden" id="set_location_popuplate" value="<?php echo $location;?>"/>
    <input type="hidden" id="process_chart" value="0"/>
    
    <noscript>
        <!-- must remain last thing before the form tag -->
        <p class="noJS">YOU NEED JAVASCRIPT TO RUN THIS SITE. PLEASE ENABLE JAVASCRIPT IN YOUR INTERNET OPTIONS.</p>
    </noscript>
    <!-- END HTML -->   
    <script>		
		$(document).ready(function() {
            $('#chart-3').css("background-image", "url("+$('#url_map').val()+")");
            var flag_show_chart = 0;
            var window_width = $(window).width();
            if(window_width > 0 && window_width <768){
                loadMiniSize();
            }else if(window_width >= 768 && window_width < 1024){
                loadAvgSize();
            }else if(window_width >=1024){
                loadMaxSize();
            }
			$('.site-header').css({"z-index":'12'});
            loadChart();
            
			// view full report 
			$('input#view_full_report').click(function() {	
				$(this).parent('.ui-btn').addClass('btn-hover');
				setTimeout(function(){
					$('.view-report-section .ui-btn').removeClass('btn-hover'); 
					window.open('<?php echo "/".$urlFolder; ?>', '_blank');
				}, 1000);				
			});
		});	
	</script> 
	<!-- InstanceEndEditable -->
    <script type="text/javascript" src="/scripts/font-chart.js"></script>
    <script type="text/javascript" src="/scripts/set-para-chart.js"></script>
    <?php
    if($deviceType=='tablet'){
    ?>
    <!--<script type="text/javascript" src="scripts/font-tablet-chart.js"></script>--> 
    <?php
    }
    ?>
    <script type="text/javascript" src="/scripts//<?php echo ($source_type == 'forecast') ? 'para-forecast-' :'para-';?>chart.js"></script>
    <script type="text/javascript" src="/scripts/<?php echo ($source_type == 'forecast') ? 'forecast-' :'';?>chart.js"></script>
	<script>
		var list = ".nav-sub";
        $(document).ready(function(){
            loadCurrentData();
        });
		$(document).bind('click', function(e){
			var objTarget = $(e.target);
			if(objTarget.parents().hasClass("nav-detail")){
				toggleIt("#nav-detail .nav-sub");
			}else if(objTarget.parents().hasClass("nav-detail_2")){
				toggleIt("#nav-detail_2 .nav-sub");
			}else if(objTarget.parents().hasClass("nav-detail_3")){
				toggleIt("#nav-detail_3 .nav-sub");
			}else if(objTarget.parents().hasClass("nav-detail_4")){
				toggleIt("#nav-detail_4 .nav-sub");
			}
		 });
		$(document).bind(eventMouse, function(e){	
			var objTarget = $(e.target);
			if(!objTarget.parents().hasClass("nav-select")){
				$(list).slideUp(200, 'linear');
			}
		});
	</script>
    <!-- === END SCRIPTS === --> 
</body>
<!-- InstanceEnd --></html>
